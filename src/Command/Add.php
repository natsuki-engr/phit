<?php

namespace App\Command;

use App\Model\File;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'add')]
class Add extends Command
{
    const OBJECT_DIR = 'objects';
    const INDEX_FILE = 'index';

    protected function configure()
    {
        $this->addArgument('pathspec', InputArgument::REQUIRED | InputArgument::IS_ARRAY);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string[] */
        $patterns = $input->getArgument('pathspec');

        $files = [];

        $filePaths = [];
        $notMatch = false;

        foreach ($patterns as $pattern) {
            $paths = [];
            if (str_contains($pattern, '*')) {
                $paths = glob($pattern);
            } else if (is_file($pattern)) {
                $paths = [$pattern];
            } else if (is_dir($pattern)) {
                $fileItr = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator(
                        $pattern,
                        FilesystemIterator::SKIP_DOTS,
                    ),
                    RecursiveIteratorIterator::LEAVES_ONLY,
                );

                /** @var SplFileInfo $file */
                foreach ($fileItr as $file) {
                    $paths[] = $file->getPathname();
                }
            }

            if ($paths === false || count($paths) === 0) {
                echo "pathspec $pattern did not match any files";
                $notMatch = true;
                break;
            }
            $filePaths = array_merge($filePaths, $paths);
        }

        if ($notMatch === true) {
            return Command::INVALID;
        }

        foreach ($filePaths as $path) {
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

            /** @var int */
            $file = fileperms($path);
            $fileMode = decoct($file);

            $files[] = new File(mode: $fileMode, path: $path, object: $sha1);
        }

        $this->updateIndex($files);

        return Command::SUCCESS;
    }

    /**
     * @param File[] $files
     */
    protected function updateIndex(array $files): void
    {
        $indexedFilesByPath = $this->readIndex();
        foreach ($files as $file) {
            $indexedFilesByPath[$file->path] = $file;
        }

        $this->writeIndex($indexedFilesByPath);
    }

    /**
     * @return array<string, File>
     */
    protected function readIndex(): array
    {
        $indexPath = Init::phitDir . '/' . self::INDEX_FILE;
        if (!file_exists($indexPath)) {
            return [];
        }

        $indexFile = file_get_contents($indexPath);
        if($indexFile === false) {
            return [];
        }

        $files = [];
        foreach (explode("\n", $indexFile) as $line) {
            if (strlen($line) === 0) {
                continue;
            }

            $blobData = explode(",", $line);
            $filePath = $blobData[2];
            $files[$filePath] = new File(mode: $blobData[0], object: $blobData[1], path: $filePath);
        }

        return $files;
    }

    /**
     * @param File[] $files
     * @return void
     */
    protected function writeIndex(array $files): void
    {
        $indexPath = Init::phitDir . '/' . self::INDEX_FILE;

        usort($files, function ($a, $b) {
            return $a->path < $b->path ? -1 : 1;
        });

        $content = implode(
            "\n",
            array_map(fn ($file) => "$file->mode,$file->object,$file->path", $files)
        );

        file_put_contents($indexPath, $content);
    }
}
