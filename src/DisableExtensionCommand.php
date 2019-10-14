<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DisableExtensionCommand extends Command
{
    use PhpVersionTrait;

    private const DESC_TEMPLATE = 'Disable a previously enabled extension for the current PHP version.';

    private const HELP_TEMPLATE = <<< 'EOH'
Disables a previouisly enabled extension for the current PHP version.
EOH;

    public function __construct(string $name = 'ext:disable')
    {
        parent::__construct($name);
    }

    protected function configure() : void
    {
        $this->setDescription(self::DESC_TEMPLATE);
        $this->setHelp(self::HELP_TEMPLATE);
        $this->addArgument(
            'extension',
            InputArgument::REQUIRED,
            'Which extension do you want to disable?'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $extension = $input->getArgument('extension');
        $version   = $this->getEnvPhpVersion();

        $command = sprintf(
            'sudo phpdismod -v %s -s cli %s',
            $version,
            escapeshellarg($extension)
        );

        passthru($command, $exitValue);

        if ($exitValue) {
            $output->writeln(
                '<error>An error occurred disabling the module.'
                . ' Please review the output for details.</error>'
            );
            return $exitValue;
        }

        return 0;
    }
}
