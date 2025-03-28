<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'add')]
class Add extends Command
{
    public static $command = 'add';
    const OBJECT_DIR = 'objects';

    protected function configure()
    {
        $this->addArgument('file_path', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = '.';
        $filePath = $input->getArgument('file_path');

        if (is_file($filePath)) {
            $addedFiles = [$filePath];
        } else {
            echo "$filePath not found such file or directory";
            return Command::INVALID;
        }

        // 差分のあるファイルだけに絞り込みが必要(file_existで確認できる？)
        foreach ($addedFiles as $path) {
            $fileContents = file_get_contents($path);
            if ($fileContents === false) {
                echo "compressing file is failed.";
                return Command::FAILURE;
            }

            $compressed = gzcompress($fileContents);
            if ($compressed === false) {
                echo "compressing file is failed.";
                return Command::FAILURE;
            }

            $sha1 = sha1($compressed);
            $dir = Init::phitDir . '/' . self::OBJECT_DIR . '/' . substr($sha1, 0, 2);
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            $objectFilePath = $dir . '/' . substr($sha1, 2, strlen($sha1) - 2);
            file_put_contents($objectFilePath, $compressed);
        }

        return Command::SUCCESS;
    }
}
