<?php
namespace Tests;

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
        $database = DatabaseFactory::create();
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
    }
}
?>
