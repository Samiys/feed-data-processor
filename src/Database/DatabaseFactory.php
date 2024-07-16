<?php
namespace App\Database;

use App\Exceptions\DatabaseConnectionException;

class DatabaseFactory {
    /**
     * @throws DatabaseConnectionException
     */
    public static function create(string $type = 'mysql'): DatabaseInterface {
        if ($type === 'mysql') {
            return new MySQLDatabase();
        }
        throw new DatabaseConnectionException("Unsupported database type: $type");
    }
}
?>
