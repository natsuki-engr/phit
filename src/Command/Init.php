<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'init')]
class Init extends Command
{
    protected static $phitDir = './.phit';

    protected static $defaultBranch = 'ref: refs/heads/main';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (is_dir(self::$phitDir)) {
            echo self::$phitDir . ' directory already exists';
            exit(1);
        } else {
            mkdir(self::$phitDir);
            self::initDir();
        }

        echo "your phit is initialized";
        return Command::SUCCESS;
    }

    protected static function initDir()
    {
        $headFIle = fopen(self::$phitDir . '/HEAD', 'w');
        fwrite($headFIle, self::$defaultBranch);

        mkdir(self::$phitDir . "/objects");

        mkdir(self::$phitDir . "/refs");
        mkdir(self::$phitDir . '/refs/heads');
    }
}
