<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EnableExtensionCommand extends Command
{
    use PhpVersionTrait;

    private const DESC_TEMPLATE = 'Enable an extension for the current PHP version.';

    private const HELP_TEMPLATE = <<< 'EOH'
Enables a previouisly installed extension for the current PHP version.
EOH;

    public function __construct(string $name = 'ext:enable')
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
            'Which extension do you want to enable?'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io        = new SymfonyStyle($input, $output);
        $extension = $input->getArgument('extension');
        $version   = $this->getEnvPhpVersion();

        $io->title(sprintf('Disabling extension %s in PHP version %s', $extension, $version));

        $command = sprintf(
            'sudo phpenmod -v %s -s cli %s',
            $version,
            escapeshellarg($extension)
        );

        passthru($command, $exitValue);

        if ($exitValue) {
            $output->writeln(
                '<error>An error occurred enabling the module.'
                . ' Please review the output for details.</error>'
            );
            return $exitValue;
        }

        return 0;
    }
}
