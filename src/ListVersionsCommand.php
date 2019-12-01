<?php

declare(strict_types=1);

namespace Mwop\Phps;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListVersionsCommand extends Command
{
    private const DESC_TEMPLATE = 'List supported PHP versions.';

    private const HELP_TEMPLATE = <<< 'EOH'
Provides a list of installed PHP versions to choose from.
EOH;

    /** @var UpdateAlternatives */
    private $updateAlternatives;

    public function __construct(string $name = 'version:list')
    {
        parent::__construct($name);
        $this->updateAlternatives = new UpdateAlternatives();
    }

    protected function configure() : void
    {
        $this->setDescription(self::DESC_TEMPLATE);
        $this->setHelp(self::HELP_TEMPLATE);
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Managed PHP versions');

        $versions = $this->updateAlternatives->getSupportedVersions();
        usort($versions, 'version_compare');

        foreach ($versions as $version) {
            $output->writeln(sprintf('- %s', $version));
        }

        return 0;
    }
}
