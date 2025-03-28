<?php

namespace App\Command;

use App\Command\Init;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'cat-file')]
class CatFile extends Command
{
    public static $command = 'cat-file';

    protected function configure()
    {
        $this->setDefinition(
            new InputDefinition([
                new InputOption('pretty-print', 'p', InputOption::VALUE_REQUIRED),
            ]),
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sha1 = $input->getOption('pretty-print');
        $dir = substr($sha1, 0, 2);
        $filename = substr($sha1, 2, strlen($sha1));
        $filePath = Init::objectDir . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($filePath)) {
            echo "Not a valid object name $sha1";
            return Command::INVALID;
        }

        $file = file_get_contents($filePath);
        $uncompressed = gzuncompress($file);
        echo "$uncompressed";

        return Command::SUCCESS;
    }
}
