<?php

require_once dirname(__FILE__) . '/CommandBase.php';

class Init extends CommandBase
{
    public static $command = 'init';

    protected static $phitDir = './.phit';

    protected static $defaultBranch = 'ref: refs/heads/main';

    public static function execute(array $args)
    {
        echo 'init';

        if (is_dir(self::$phitDir)) {
            echo self::$phitDir . ' directory already exists';
            exit(1);
        } else {
            mkdir(self::$phitDir);
            self::initDir();
        }

        echo "your phit is initialized";
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
