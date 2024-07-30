<?php
namespace Tests;

use App\Database\DatabaseDTO;
use App\Database\DatabaseFactory;
use App\Exceptions\DatabaseConnectionException;
use PHPUnit\Framework\TestCase;
use App\DataHandler\DataInserter;
use App\Repository\ItemRepository;

class DataInserterTest extends TestCase {
    private $db;
    private $dataInserter;

    /**
     * @throws DatabaseConnectionException
     */
    protected function setUp(): void {
        $configArray = require __DIR__ . '/../config/config.php';
        $configArray['db']['connections']['mysql']['host'] = '127.0.0.1';
        $configArray['db']['connections']['pgsql']['host'] = '127.0.0.1';
        $config = new DatabaseDTO($configArray);
        $databaseFactory = new DatabaseFactory($config);
        $database = $databaseFactory->create();
        $this->db = $database->getConnection();
        $database->initializeDatabase();
        $itemRepository = new ItemRepository($this->db);
        $this->dataInserter = new DataInserter($itemRepository);
    }

    public function testInsertData(): void
    {
        $xmlFile = __DIR__ . '/../feed.xml';
        $xml = simplexml_load_string(file_get_contents($xmlFile));
        $this->dataInserter->insertData($xml);

        $stmt = $this->db->query("SELECT COUNT(*) FROM items");
        $count = $stmt->fetchColumn();
        $this->assertGreaterThan(0, $count);

        $stmt = $this->db->query("SELECT * FROM items WHERE entity_id = 340");
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->assertNotEmpty($row);
        $this->assertEquals('340', $row['entity_id']);
        $this->assertEquals('Green Mountain Ground Coffee', $row['category_name']);
    }
}
?>
