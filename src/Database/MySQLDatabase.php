<?php
namespace App\Database;

use App\Exceptions\DatabaseConnectionException;
use App\Logger\ErrorLogger;
use PDO;
use PDOException;

class MySQLDatabase implements DatabaseInterface {
    private $connection;
    private $config;

    /**
     * @throws DatabaseConnectionException
     */
    public function __construct(array $config) {
        $this->config = $config;
        $this->initializeConnection();
    }

    public function getConnection(): PDO {
        return $this->connection;
    }

    /**
     * @throws DatabaseConnectionException
     */
    private function initializeConnection(): void {
        try {
            $dbConfig = $this->config;
            $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
            $this->connection = new PDO($dsn, $dbConfig['user'], $dbConfig['password']);
            $this->initializeDatabase();
        } catch (PDOException $e) {
            throw new DatabaseConnectionException("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * @throws DatabaseConnectionException
     */
    public function initializeDatabase(): void
    {
        try {
            $this->connection->exec("CREATE TABLE IF NOT EXISTS items (
                entity_id INT PRIMARY KEY,
                category_name VARCHAR(255),
                sku VARCHAR(255),
                name VARCHAR(255),
                description TEXT,
                short_desc TEXT,
                price DECIMAL(10, 2),
                link TEXT,
                image TEXT,
                brand VARCHAR(255),
                rating INT,
                caffeine_type VARCHAR(255),
                count INT,
                flavored VARCHAR(255),
                seasonal VARCHAR(255),
                in_stock VARCHAR(255),
                facebook INT,
                is_kcup INT
            )");
        } catch (PDOException $e) {
            ErrorLogger::logError("Failed to initialize database: " . $e->getMessage());
            throw new DatabaseConnectionException("Failed to initialize database", 0, $e);
        }
    }
}
?>
