<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'init')]
class Init extends Command
{
    const phitDir = './.phit';
    const objectDir = self::phitDir  . DIRECTORY_SEPARATOR . "objects";

    protected static $defaultBranch = 'ref: refs/heads/main';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (is_dir(self::phitDir)) {
            echo self::phitDir . ' directory already exists';
            exit(1);
        } else {
            mkdir(self::phitDir);
            self::initDir();
        }

        echo "your phit is initialized";
        return Command::SUCCESS;
    }

    /**
     * @return void
     */
    protected static function initDir()
    {
        $headFIle = fopen(self::phitDir . '/HEAD', 'w');
        if(!$headFIle) {
            echo "couldn't find HEAD";
            exit();
        }

        fwrite($headFIle, self::$defaultBranch);

        mkdir(self::objectDir);

        mkdir(self::phitDir . "/refs");
        mkdir(self::phitDir . '/refs/heads');
    }
}
