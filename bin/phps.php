#!/usr/bin/env php
<?php

declare(strict_types=1);

namespace Mwop\Phps;

use PackageVersions\Versions;
use Symfony\Component\Console\Application;

// Setup/verify autoloading
if (file_exists($a = __DIR__ . '/../../../autoload.php')) {
    require $a;
} elseif (file_exists($a = __DIR__ . '/../vendor/autoload.php')) {
    require $a;
} elseif (file_exists($a = __DIR__ . '/../autoload.php')) {
    require $a;
} else {
    fwrite(STDERR, 'Cannot locate autoloader; please run "composer install"' . PHP_EOL);
    exit(1);
}

$version     = strstr(Versions::getVersion('weierophinney/phps'), '@', true);
$application = new Application('phps', $version);

$application->addCommands([
    new AddRepoCommand(),
    new ConfigCommand(),
    new DisableExtensionCommand(),
    new DisableVersionCommand(),
    new EnableExtensionCommand(),
    new EnableVersionCommand(),
    new InitializeCommand(),
    new InstallExtensionCommand(),
    new InstallVersionCommand(),
    new ListVersionsCommand(),
    new SwitchCommand(),
    new UninstallVersionCommand(),
    new UseCommand(),
]);
$application->run();
