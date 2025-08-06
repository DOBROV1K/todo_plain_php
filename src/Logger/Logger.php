<?php

namespace Src\Logger;

class Logger
{
    private static ?Logger $instance = null;
    private string $logFile;

    private function __construct()
    {
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $this->logFile = $logDir . '/app.log';
    }

    public static function getInstance(): Logger
    {
        if (!self::$instance) {
            self::$instance = new Logger();
        }
        return self::$instance;
    }

    public function write(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $line = "[$timestamp] $message" . PHP_EOL;
        file_put_contents($this->logFile, $line, FILE_APPEND);
    }
}
