<?php
namespace App\Database;

use App\Exceptions\DatabaseConnectionException;
use App\Logger\ErrorLogger;
use PDO;
use PDOException;

class MySQLDatabase implements DatabaseInterface {
    private $connection;

    /**
     * @throws DatabaseConnectionException
     */
    public function __construct() {
        $this->initializeConnection();
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @throws DatabaseConnectionException
     */
    private function initializeConnection(): void {
        try {
            $config = require __DIR__ . '/../../config/config.php';
            $host = $config['db']['host'] ?? 'localhost';
            $dbname = $config['db']['dbname'] ?? '';
            $user = $config['db']['user'] ?? '';
            $password = $config['db']['password'] ?? '';

            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

            $this->connection = new PDO($dsn, $user, $password);
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
