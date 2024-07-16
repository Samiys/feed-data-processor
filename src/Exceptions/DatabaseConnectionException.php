<?php
namespace App\Exceptions;

class DatabaseConnectionException extends \Exception
{
    public function __construct(string $message = "Database connection failed", int $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
