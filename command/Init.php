<?php

require_once dirname(__FILE__) . '/CommandBase.php';

class Init extends CommandBase {
    public static $command = 'init';

    protected static $phitDir = './.phit';

    public static function execute(array $args) {
        echo 'init';

        if(is_dir(self::$phitDir)) {
            echo self::$phitDir . ' directory already exists';
            exit(1);
        } else {
            mkdir(self::$phitDir);
        }
    }
}
