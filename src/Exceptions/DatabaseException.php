<?php
namespace App\Exceptions;

use PDOException;

class DatabaseException extends PDOException {
    public function __construct($message = "Database operation failed", $code = 0, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
?>
