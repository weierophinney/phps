<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCommand extends Command
{
    use PhpVersionTrait;

    private const CONFIG_TEMPLATE = <<< 'EOC'
; Machine-specific PHP settings.
; priority=99
;
; Add any PHP settings you wish to set, override, etc. to this file. By default,
; 'phps config' will run 'phpenmod' to ensure this configuration is used; the
; priority above will be used, ensuring it overrides any other settings defined
; elsewhere.
;
; If you are unsure what settings are available, look in ../{SAPI}/php.ini.

EOC;

    private const DESC_TEMPLATE = 'Configure the current PHP version.';

    private const HELP_TEMPLATE = <<< 'EOH'
Opens the php.ini associated with the current PHP version in $EDITOR as root.
EOH;

    public function __construct(string $name = 'config')
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
        $version = $this->getEnvPhpVersion();
        $path    = $this->getConfigFile($version, $output);

        if (! $path) {
            return 1;
        }

        $editor  = getenv('EDITOR') ?? 'vim';
        $command = sprintf('sudo %s %s', $editor, escapeshellarg($path));

        $process = proc_open($command, [STDIN, STDOUT, STDERR], $pipes);
        $exitValue = proc_close($process);

        return $exitValue;
    }

    private function getConfigFile(string $version, OutputInterface $output) : ?string
    {
        $path = sprintf('/etc/php/%s/mods-available/phps.ini', $version);

        if (! file_exists($path)) {
            $exitValue = $this->createConfigFile($path, $version, $output);
            return $exitValue ? null : $path;
        }

        return $path;
    }

    private function createConfigFile(string $path, string $version, OutputInterface $output) : int
    {
        $output->writeln(sprintf('<info>Creating %s...</info>', $path));
        $command = sprintf('echo "%s" | sudo tee %s', self::CONFIG_TEMPLATE, escapeshellarg($path));
        passthru($command, $exitValue);

        if (0 !== $exitValue) {
            $output->writeln('<error>Unable to create config file! Please see the above output for details.');
            return $exitValue;
        }

        $output->writeln(sprintf('<info>Registering phps for PHP %s...</info>', $version));
        $command = sprintf('sudo phpenmod -v %s -s cli phps', $version);
        passthru($command, $exitValue);

        if (0 !== $exitValue) {
            $output->writeln('<error>Unable to register phps configuration! Please see the above output for details.');
            return $exitValue;
        }

        return 0;
    }
}
