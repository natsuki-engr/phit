<?php

require_once dirname(__FILE__) . '/CommandBase.php';

class Add extends CommandBase
{
    public static $command = 'add';
    const OBJECT_DIR = 'objects';

    public static function execute(array $args)
    {
        /** @var string */
        $filePath = '.';
        if (count($args) !== 0) {
            $filePath = $args[0];
        }

        if (is_file($filePath)) {
            $addedFiles = [$filePath];
        } else {
            echo $filePath . " not found such file or directory";
            exit(1);
        }

        // 差分のあるファイルだけに絞り込みが必要(file_existで確認できる？)
        foreach ($addedFiles as $path) {
            $fileContents = file_get_contents($path);
            if ($fileContents === false) {
                echo "compressing file is failed.";
                exit(1);
            }

            $compressed = gzcompress($fileContents);
            if ($compressed === false) {
                echo "compressing file is failed.";
                exit(1);
            }

            $sha1 = sha1($compressed);
            $dir = Init::phitDir . '/' . self::OBJECT_DIR . '/' . substr($sha1, 0, 2);
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            $objectFilePath = $dir . '/' . substr($sha1, 2, strlen($sha1) - 2);
            file_put_contents($objectFilePath, $compressed);
        }
    }
}
