<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SwitchCommand extends Command
{
    private const DESC_TEMPLATE = 'Choose which PHP version to use by default.';

    private const HELP_TEMPLATE = <<< 'EOH'
Switches to the provided PHP version (in major.minor format) for use as the
default version across all shells.
EOH;

    /** @var UpdateAlternatives */
    private $updateAlternatives;

    public function __construct(string $name = 'switch')
    {
        parent::__construct($name);
        $this->updateAlternatives = new UpdateAlternatives();
    }

    protected function configure() : void
    {
        $this->setDescription(self::DESC_TEMPLATE);
        $this->setHelp(self::HELP_TEMPLATE);
        $this->addArgument(
            'version',
            InputArgument::OPTIONAL,
            'Which PHP version do you want to use (auto to use latest)?',
            'auto'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $version = $input->getArgument('version');
        $version = 'auto' === $version
            ? $this->updateAlternatives->getBestVersion($output)
            : $version;

        $versions = $this->updateAlternatives->getSupportedVersions();
        if (! in_array($version, $versions, true)) {
            $output->writeln(sprintf(
                '<error>Unsupported version; must be one of [%s]</error>',
                implode(', ', $versions)
            ));
            return 1;
        }

        $exitValue = $this->updateAlternatives->switchVersion($version, $output);

        if ($exitValue === 0) {
            $output->writeln(sprintf('<info>Now using PHP version %s</info>', $version));
            $output->writeln('<info>If you have previously aliased to a specific version, run:</info>');
            $output->writeln('    unalias php');
        }

        return $exitValue;
    }
}
