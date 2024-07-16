<?php
namespace App\Logger;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ErrorLogger {
    private static $logger;

    private static function getLogger(): Logger
    {
        if (!self::$logger) {
            self::$logger = new Logger('app_logger');
            self::$logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/app.log', Logger::ERROR));
        }
        return self::$logger;
    }

    public static function logError(string $message, array $context = []): void
    {
        self::getLogger()->error($message, $context);
    }
}
?>
