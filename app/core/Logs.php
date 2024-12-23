<?php

namespace app\core;

class Logs
{
    private static string $fileDir = 'storage\logs';

    public static function write(array|string $contents): void
    {
        $file = self::$fileDir . DIRECTORY_SEPARATOR . 'log_' . date("Y-m-d") . '.log';
        if(is_array($contents)) {
            foreach ($contents as $content) {
                $log = "[" . date("Y-m-d h:i:s") . "] : " . $content . PHP_EOL;
                file_put_contents($file, $log, FILE_APPEND);
            }
        }else{
            $log = "[" . date("Y-m-d h:i:s") . "] : " . $contents . PHP_EOL;
            file_put_contents($file, $log, FILE_APPEND);
        }

    }
}