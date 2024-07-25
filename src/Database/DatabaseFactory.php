<?php
namespace App\Database;

use App\Exceptions\DatabaseConnectionException;
use App\Config\DatabaseConfig;

class DatabaseFactory {
    private $config;

    public function __construct(DatabaseDTO $config) {
        $this->config = $config;
    }

    /**
     * @throws DatabaseConnectionException
     */
    public function create(string $type = 'mysql'): DatabaseInterface {
        switch ($type) {
            case 'mysql':
                return new MySQLDatabase($this->config->getMySQLConfig());
            case 'pgsql':
                return new PostgreSQLDatabase($this->config->getPgSQLConfig());
            default:
                throw new DatabaseConnectionException("Unsupported database type: $type");
        }
    }
}
?>
