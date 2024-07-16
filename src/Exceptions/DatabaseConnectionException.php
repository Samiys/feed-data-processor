<?php
namespace App\Exceptions;

class DatabaseConnectionException extends \Exception
{
    public function __construct($message = "Database connection failed", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
