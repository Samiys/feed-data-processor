<?php
namespace App\Database;

use App\Exceptions\DatabaseConnectionException;
use PDO;
use PDOException;

class PostgreSQLDatabase implements DatabaseInterface {
    private $connection;

    /**
     * @throws DatabaseConnectionException
     */
    public function __construct() {
        $this->initializeConnection();
    }

    public function getConnection(): PDO {
        return $this->connection;
    }

    private function initializeConnection(): void {
        try {
            $config = require __DIR__ . '/../../config/config.php';
            $dbConfig = $config['db']['connections']['pgsql'];
            $host = $dbConfig['host'];
            $dbname = $dbConfig['dbname'];
            $user = $dbConfig['user'];
            $password = $dbConfig['password'];
            $port = $dbConfig['port'];
            $charset = $dbConfig['charset'];

            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;options='--client_encoding=$charset'";

            $this->connection = new PDO($dsn, $user, $password);
            $this->initializeDatabase();
        } catch (PDOException $e) {
            throw new DatabaseConnectionException("Database connection failed: " . $e->getMessage());
        }
    }

    public function initializeDatabase(): void {
        try {
            $this->connection->exec("CREATE TABLE IF NOT EXISTS items (
                entity_id SERIAL PRIMARY KEY,
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
            throw new DatabaseConnectionException("Failed to initialize database: " . $e->getMessage());
        }
    }
}
?>
