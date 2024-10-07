<?php

require_once dirname(__FILE__) . '/CommandBase.php';

class Add extends CommandBase
{
    public static $command = 'add';

    public static function execute(array $args)
    {
        echo 'add';

        
    }
}
