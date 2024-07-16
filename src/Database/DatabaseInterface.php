<?php
namespace App\Database;

interface DatabaseInterface {
    public function getConnection(): \PDO;
    public function initializeDatabase(): void;
}
?>

