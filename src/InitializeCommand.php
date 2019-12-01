<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InitializeCommand extends Command
{
    private const ALTERNATIVES_TEMPLATE = <<< 'EOT'
manual
%s/.local/bin/php


EOT;

    private const DESC_TEMPLATE = 'Prepare your environment to manage different PHP versions.';

    private const HELP_TEMPLATE = <<< 'EOH'
Prepares your local environment to manage different PHP versions by creating
files that allow update-alternatives to know what PHP binaries it should
manage.
EOH;

    public function __construct(string $name = 'init')
    {
        parent::__construct($name);
    }

    protected function configure() : void
    {
        $this->setDescription(self::DESC_TEMPLATE);
        $this->setHelp(self::HELP_TEMPLATE);
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Initializing PHP alternatives');

        $home             = getenv('HOME');
        $alternativesFile = sprintf('%s/.local/var/lib/alternatives/php', $home);

        if (file_exists($alternativesFile)) {
            $io->success('Your environment is already prepared!');
            return 0;
        }

        $create = $io->confirm(
            sprintf('Do you want to create the file %s to handle your PHP versions?', $alternativesFile),
            true
        );

        if (! $create) {
            $io->error('Aborting');
            return 1;
        }

        $dir = dirname($alternativesFile);
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($alternativesFile, sprintf(self::ALTERNATIVES_TEMPLATE, $home));

        $io->success('Alternatives file created');

        return 0;
    }
}
