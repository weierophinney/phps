<?php

declare(strict_types=1);

namespace Mwop\Phps;

use DirectoryIterator;
use FilterIterator;
use SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddRepoCommand extends Command
{
    private const DESC_TEMPLATE = 'Add the packages.sury.org repository to your apt sources list.';

    private const HELP_TEMPLATE = <<< 'EOH'
Adds the deb.sury.org repository to your apt sources list.

It first checks to see if a list entry already exists for packages.sury.org or
ppa:ondrej. If so, it aborts early with a success status.

Otherwise, it will add the packages.sury.org repository via a new apt
sources.list.d file, and then run apt-get update.
EOH;

    public function __construct(string $name = 'repo:add')
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
        $io = new SymfonyStyle($input, $output);
        $io->title('Injecting packages.sury.org repository');

        $repoExists = $this->repositoryEntryExists($io);
        if (null === $repoExists) {
            return 1;
        }

        if ($repoExists) {
            $io->success('Repo is already present on your system');
            return 0;
        }

        if (! $this->injectRepo($io)) {
            $io->error('Unable to inject packages.sury.org repository; see above logs for details');
            return 1;
        }

        $io->success('Repo injected and ready!');
        return 0;
    }

    private function repositoryEntryExists(SymfonyStyle $io) : ?bool
    {
        $path = '/etc/apt/sources.list.d';
        if (! is_dir($path)) {
            $io->error('This command only works on Debian-based operating systems.');
            return null;
        }
        $directory = $this->createFilter(new DirectoryIterator($path));

        foreach ($directory as $file) {
            /** @var SplFileInfo $file */
            $contents = file_get_contents($file->getPathname());
            if (strpos($contents, 'packages.sury.org') !== 0
                || strpos($contents, 'ppa.launchpad.net/ondrej/php') !== 0
            ) {
                return true;
            }
        }

        return false;
    }

    private function createFilter(DirectoryIterator $directory) : FilterIterator
    {
        return new class($directory) extends FilterIterator {
            public function accept()
            {
                /** @var SplFileInfo $file */
                $file = $this->getInnerIterator()->current();
                return $file->isFile() && $file->getExtension() === 'list';
            }
        };
    }

    private function injectRepo(SymfonyStyle $io) : bool
    {
        $status = 0;

        $io->text('<info>Installing required dependencies</info>');
        passthru('sudo apt-get -y install apt-transport-https lsb-release ca-certificates curl', $status);
        if ($status !== 0) {
            return false;
        }

        $io->text('<info>Retrieving and installing repository GPG key</info>');
        passthru('sudo curl https://packages.sury.org/php/apt.gpg --output /etc/apt/trusted.gpg.d/php.gpg', $status);
        if ($status !== 0) {
            return false;
        }

        $io->text('<info>Adding repository /etc/apt/sources.list.d/php.list</info>');
        passthru('sudo sh -c \'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list\'');
        if ($status !== 0) {
            return false;
        }

        $io->text('<info>Updating dependency list</info>');
        passthru('sudo apt-get update');
        if ($status !== 0) {
            return false;
        }

        return true;
    }
}
