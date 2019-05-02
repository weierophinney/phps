<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UseCommand extends Command
{
    private const DESC_TEMPLATE = 'Choose which PHP version to use in the current session.';

    private const HELP_TEMPLATE = <<< 'EOH'
Details how to use the provided PHP version (in major.minor format) within the
current shell session.
EOH;

    /** @var UpdateAlternatives */
    private $updateAlternatives;

    public function __construct(string $name = 'use')
    {
        parent::__construct($name);
        $this->updateAlternatives = new UpdateAlternatives();
    }

    protected function configure() : void
    {
        $this->setDescription(self::DESC_TEMPLATE);
        $this->setHelp(self::HELP_TEMPLATE);
        $this->addArgument(
            'version',
            InputArgument::OPTIONAL,
            'Which PHP version do you want to use (auto to use latest)?',
            'auto'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $version = $input->getArgument('version');
        $version = 'auto' === $version
            ? $this->updateAlternatives->getBestVersion($output)
            : $version;

        $versions = $this->updateAlternatives->getSupportedVersions();
        if (! in_array($version, $versions, true)) {
            $output->writeln(sprintf(
                '<error>Unsupported version; must be one of [%s]</error>',
                implode(', ', $versions)
            ));
            return 1;
        }

        $output->writeln(
            '<info>Cut and paste the following line to temporarily switch to the selected PHP version:</info>'
        );
        $output->writeln(sprintf('    alias php=/usr/bin/php%s', $version));

        return 0;
    }
}
