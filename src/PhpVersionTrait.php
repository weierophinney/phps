<?php

declare(strict_types=1);

namespace Mwop\Phps;

trait PhpVersionTrait
{
    private function getEnvPhpVersion() : string
    {
        $output = shell_exec('/usr/bin/env php -v');
        preg_match('/^php\s+(?P<version>\d+\.\d+)\./i', $output, $matches);
        return $matches['version'];
    }
}
