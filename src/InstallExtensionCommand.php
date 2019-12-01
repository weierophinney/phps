<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InstallExtensionCommand extends Command
{
    use PhpVersionTrait;

    private const DESC_TEMPLATE = 'Install an extension for the current PHP version.';

    private const HELP_TEMPLATE = <<< 'EOH'
Compiles, installs, and enables an extension for the current PHP version.
EOH;

    public function __construct(string $name = 'ext:install')
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
            'Which extension do you want to install?'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io        = new SymfonyStyle($input, $output);
        $extension = $input->getArgument('extension');
        $version   = $this->getEnvPhpVersion();

        $io->title(sprintf('Installing extension %s for PHP version %s', $extension, $version));

        $command = sprintf(
            'sudo pecl -d php_suffix=%s install %s && sudo pecl uninstall -r %s',
            $version,
            escapeshellarg($extension),
            escapeshellarg($extension)
        );

        passthru($command, $exitValue);

        if ($exitValue) {
            $output->writeln('<error>An error occurred; please review the output for details.</error>');
            return $exitValue;
        }

        $config = <<< 'EOC'
; configuration for php %s module
; priority=20
extension=%s.so
EOC;
        $config     = sprintf($config, $extension, $extension);
        $configFile = sprintf('/etc/php/%s/mods-available/%s.ini', $version, $extension);
        $command    = sprintf('echo "%s" | sudo tee %s', $config, escapeshellarg($configFile));

        passthru($command, $exitValue);

        if ($exitValue) {
            $output->writeln(
                '<error>An error occurred creating the module configuration file.'
                . ' Please review the output for details.</error>'
            );
            return $exitValue;
        }

        return $this->enableExtension($extension, $output);
    }

    private function enableExtension(string $extension, OutputInterface $output) : int
    {
        $command = $this->getApplication()->find('ext:enable');
        $input = new ArrayInput([
            'command'   => 'ext:enable',
            'extension' => $extension,
        ]);

        return $command->run($input, $output);
    }
}
