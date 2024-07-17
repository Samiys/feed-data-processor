<?php
namespace App\Database;

use App\Exceptions\DatabaseConnectionException;

class DatabaseFactory {
    /**
     * @throws DatabaseConnectionException
     */
    public static function create(string $type = 'mysql'): DatabaseInterface {
        switch ($type) {
            case 'mysql':
                return new MySQLDatabase();
            case 'pgsql':
                return new PostgreSQLDatabase();
            default:
                throw new DatabaseConnectionException("Unsupported database type: $type");
        }
    }
}
?>
