<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EnableVersionCommand extends Command
{
    private const ALTERNATIVES_TEMPLATE = <<< 'EOT'
manual
%s/.local/bin/php


EOT;

    private const DESC_TEMPLATE = 'Enable a newly installed PHP version.';

    private const HELP_TEMPLATE = <<< 'EOH'
Enables a previously installed PHP version, exposing it to allow switching
to and from the new version.

When providing a version, provide it in {MAJOR}.{MINOR} format.
EOH;

    public function __construct(string $name = 'version:enable')
    {
        parent::__construct($name);
    }

    protected function configure() : void
    {
        $this->setDescription(self::DESC_TEMPLATE);
        $this->setHelp(self::HELP_TEMPLATE);
        $this->addArgument(
            'version',
            InputArgument::REQUIRED,
            'Which version do you want to enable?'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io      = new SymfonyStyle($input, $output);
        $version = $input->getArgument('version');
        $binary  = $this->getBinaryLocation($version, $io);
        if (null === $binary) {
            return 1;
        }

        $alternativesFile = $this->getAlternativesFile($io);
        if (null === $alternativesFile) {
            return 1;
        }

        $alternatives = file_get_contents($alternativesFile);
        if ($this->binaryAlreadyRegistered($binary, $alternatives)) {
            $io->success('Binary already registered!');
            return 0;
        }

        $alternatives = $this->appendVersion($version, $binary, $alternatives);
        file_put_contents($alternativesFile, $alternatives);

        $io->success('Done!');
        return 0;
    }

    private function getBinaryLocation(string $version, SymfonyStyle $io) : ?string
    {
        $binary  = sprintf('/usr/bin/php%s', $version);

        if (file_exists($binary)) {
            return $binary;
        }

        $io->error(sprintf(
            'Unable to find binary for version "%s"; executable "%s" not found',
            $version,
            $binary
        ));

        return null;
    }

    private function getAlternativesFile(SymfonyStyle $io) : ?string
    {
        $alternativesFile = sprintf('%s/.local/var/lib/alternatives/php', getenv('HOME'));
        if (file_exists($alternativesFile)) {
            return $alternativesFile;
        }

        $create = $io->confirm(
            sprintf('Do you want to create the file %s to handle your PHP versions?', $alternativesFile),
            true
        );

        if (! $create) {
            $io->success('Aborting');
            return null;
        }

        $dir = dirname($alternativesFile);
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($alternativesFile, sprintf(self::ALTERNATIVES_TEMPLATE, getenv('HOME')));

        return $alternativesFile;
    }

    private function binaryAlreadyRegistered(string $binary, string $alternatives) : bool
    {
        return strpos($alternatives, $binary) !== false;
    }

    private function appendVersion(string $version, string $binary, string $alternatives) : string
    {
        return sprintf(
            "%s\n%s\n%s\n\n",
            trim($alternatives),
            $binary,
            str_replace('.', '0', $version)
        );
    }
}
