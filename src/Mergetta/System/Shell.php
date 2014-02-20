<?php namespace Mergetta\System;

/**
 * Class Shell
 * @author Karabutin Alex <karabutinalex@gmail.com>
 * @package Mergetta\System
 */
class Shell {

    /**
     * Выполнить команду в фоне, не дожидаясь ее завершения
     * @param $command
     */
    public static function executeInBackground($command)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            @popen("start /b {$command} > NUL", 'r');
        } else {
            @shell_exec("{$command} &");
        }
    }

}