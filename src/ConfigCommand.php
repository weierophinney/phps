<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCommand extends Command
{
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
        $version = sprintf('%s.%s', PHP_MAJOR_VERSION, PHP_MINOR_VERSION);
        $path    = sprintf('/etc/php/%s/cli/php.ini', $version);

        $editor  = getenv('EDITOR') ?? 'vim';
        $command = sprintf('sudo %s %s', $editor, escapeshellarg($path));

        $process = proc_open($command, [STDIN, STDOUT, STDERR], $pipes);
        $exitValue = proc_close($process);

        return $exitValue;
    }
}
