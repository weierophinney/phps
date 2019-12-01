<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InstallVersionCommand extends Command
{
    private const DESC_TEMPLATE = 'Install a new PHP version.';

    private const HELP_TEMPLATE = <<< 'EOH'
Installs and enables a new PHP version, with any additional packages as
provided in the <ext> arguments.  The "common", "cli", and "dev" packages for
the given version are always installed

When providing a version, provide it in {MAJOR}.{MINOR} format.
EOH;

    public function __construct(string $name = 'version:install')
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
            'Which version do you want to install?'
        );
        $this->addArgument(
            'ext',
            InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
            'Which additional extensions do you wish to install? (Provide package names minus "php{version}-" prefix)'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io         = new SymfonyStyle($input, $output);
        $version    = $input->getArgument('version');
        $extensions = array_merge(['common', 'cli', 'dev'], $input->getArgument('ext'));

        $io->title(sprintf('Installing PHP version %s', $version));

        if (! $this->install($version, $extensions)) {
            $io->error(sprintf(
                'Error installing PHP %s (with extensions %s); check logs for details',
                $version,
                implode(', ', $extensions)
            ));
            return 1;
        }

        $command = $this->getApplication()->find('version:enable');
        $result  = $command->run(new ArrayInput([
            'command' => 'version:enable',
            'version' => $version,
        ]), $output);

        return $result;
    }

    private function install(string $version, array $extensions) : bool
    {
        $packages = array_map(function ($ext) use ($version) {
            return sprintf('php%s-%s', $version, $ext);
        }, $extensions);
        $command = sprintf('sudo apt -y install %s', implode(' ', $packages));
        passthru($command, $return);
        return 0 === $return;
    }
}
