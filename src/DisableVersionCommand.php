<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DisableVersionCommand extends Command
{
    private const DESC_TEMPLATE = 'Disable a PHP version.';

    private const HELP_TEMPLATE = <<< 'EOH'
Removes a PHP version from the list of alternatives, which will prevent it from
being displayed by version:list, as well as prevent the ability to use/switch
to the version, or enable/disable extensions for it.

Typically, only use this after uninstalling the version from your computer.

When providing a version, provide it in {MAJOR}.{MINOR} format.
EOH;

    public function __construct(string $name = 'version:disable')
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
            'Which version do you want to disable?'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io      = new SymfonyStyle($input, $output);
        $version = $input->getArgument('version');

        $alternativesFile = sprintf('%s/.local/var/lib/alternatives/php', getenv('HOME'));
        if (! file_exists($alternativesFile)) {
            $io->caution('You do not appear to have initialized your environment yet.');
            $io->text(
                'You should run "init", along with one or more "version:enable"'
                . ' commands before attempting to disable a version.'
            );
            return 1;
        }

        $alternatives = file_get_contents($alternativesFile);
        $binary       = sprintf('/usr/bin/php%s', $version);

        if (! $this->binaryAlreadyRegistered($binary, $alternatives)) {
            $io->caution('Version does not appear to be registered.');
            return 0;
        }

        $updated = $this->removeVersion($binary, $alternatives);
        if (null === $updated) {
            $io->error('The attempt to remove the version failed');
            $io->text(sprintf(
                'You will need to manuall edit the file %s to remove the entry for %s.',
                $alternativesFile,
                $binary
            ));
            return 1;
        }

        file_put_contents($alternativesFile, $updated);

        $io->success('Done!');
        return 0;
    }

    private function binaryAlreadyRegistered(string $binary, string $alternatives) : bool
    {
        return strpos($alternatives, $binary) !== false;
    }

    private function removeVersion(string $binary, string $alternatives) : ?string
    {
        $pattern = sprintf("#\n%s\n\d+#s", $binary);
        $updated = preg_replace($pattern, '', $alternatives);
        return $updated === $alternatives ? null : $updated;
    }
}
