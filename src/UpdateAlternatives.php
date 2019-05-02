<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Output\OutputInterface;

class UpdateAlternatives
{
    // @phpcs:disable
    private const COMMAND = 'update-alternatives --altdir ~/.local/etc/alternatives --admindir ~/.local/var/lib/alternatives';
    // @phpcs:enable

    public function getSupportedVersions(?OutputInterface $output = null) : ?array
    {
        $command = sprintf('%s --list php', self::COMMAND);
        exec($command, $commandOutput, $return);
        if (0 !== $return) {
            $this->reportError($output, sprintf('Unable to list supported versions; exited with status %d', $return));
            return null;
        }

        return array_map(function ($binary) {
            $binary = basename($binary);
            return str_replace('php', '', $binary);
        }, $commandOutput);
    }

    public function getBestVersion(?OutputInterface $output = null) : ?string
    {
        $command       = sprintf('%s --query php', self::COMMAND);
        $commandOutput = shell_exec($command);

        if (! preg_match('/^Best: (?P<binary>[^\s]+)$/m', $commandOutput, $matches)) {
            $versions = $this->getSupportedVersions($output);
            if (! $versions) {
                return null;
            }

            sort($versions);
            return array_pop($versions);
        }

        $binary = $matches['binary'];
        $binary = basename($binary);
        return str_replace('php', '', $binary);
    }

    public function switchVersion(string $version, ?OutputInterface $output = null) : int
    {
        $binary  = sprintf('/usr/bin/php%s', $version);
        $command = sprintf('%s --set php %s', self::COMMAND, $binary);

        exec($command, $commandOutput, $return);
        if (0 !== $return) {
            $this->reportError($output, sprintf(
                "Unable to switch PHP version; exited with status %d:\n\n%s\n",
                $return,
                implode("\n", $commandOutput)
            ));
            return 1;
        }

        return 0;
    }

    private function reportError(?OutputInterface $output, string $message) : void
    {
        if (! $output) {
            return;
        }

        $output->writeln(sprintf('<error>%s</error>', $message));
    }
}
