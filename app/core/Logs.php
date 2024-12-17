<?php

namespace app\core;

class Logs
{
    private static string $fileDir = '\storage\logs';

    public static function write(string $content): void
    {
        $file = self::$fileDir . DIRECTORY_SEPARATOR . 'log_' . date("Y-m-d") . '.log';
        $log  = "[". date("Y-m-d h:i:s") . "] : " . $content . PHP_EOL;
        file_put_contents($file, $content, FILE_APPEND);

    }
}