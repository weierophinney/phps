<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UninstallVersionCommand extends Command
{
    private const DESC_TEMPLATE = 'Uninstall a PHP version.';

    private const HELP_TEMPLATE = <<< 'EOH'
Uninstalls and disables a PHP version, along with all packages installed for
that PHP version.

When providing a version, provide it in {MAJOR}.{MINOR} format.
EOH;

    private const LIST_PACKAGES_TEMPLATE = 'dpkg -l | grep ^ii | awk \'{print $2}\' | grep php%s';

    public function __construct(string $name = 'version:uninstall')
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
            'Which version do you want to uninstall?'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io         = new SymfonyStyle($input, $output);
        $version    = $input->getArgument('version');

        $io->title(sprintf('Uninstalling PHP version %s', $version));

        if (! $this->uninstall($version, $io)) {
            $io->error(sprintf('Error uninstalling PHP %s; check logs for details', $version));
            return 1;
        }

        $command = $this->getApplication()->find('version:disable');
        $result  = $command->run(new ArrayInput([
            'command' => 'version:disable',
            'version' => $version,
        ]), $output);

        return $result;
    }

    private function uninstall(string $version, SymfonyStyle $io) : bool
    {
        $packages = $this->getPackageList($version, $io);
        if (null === $packages) {
            return false;
        }

        $command = sprintf('sudo apt -y remove %s', implode(' ', $packages));
        passthru($command, $return);
        return 0 === $return;
    }

    private function getPackageList(string $version, SymfonyStyle $io) : ?array
    {
        $command = sprintf(self::LIST_PACKAGES_TEMPLATE, $version);
        $packages = shell_exec($command);
        if (null === $packages) {
            $io->error('No matching packages found!');
            return null;
        }
        return explode("\n", trim($packages));
    }
}
