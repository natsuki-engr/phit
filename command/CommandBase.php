<?php

abstract class CommandBase {
    /** @var string */
    public static $command;

    /**
     * @param array<string> $args
     * @return void
     */
    public static function execute(array $args) {}
}
