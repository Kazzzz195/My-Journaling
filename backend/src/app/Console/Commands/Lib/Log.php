<?php

namespace App\Lib;

class Log
{
    public static function write(string $message, string $log_file_name = 'error.log')
    {
        $file = __DIR__ . "/../../storage/logs/{$log_file_name}";

        $date = date('Y-m-d H:i:s');

        $log_message = "{$date}:{$message}\n";

        file_put_contents($file, $log_message, FILE_APPEND | LOCK_EX);
    }

}