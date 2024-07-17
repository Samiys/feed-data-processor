<?php

require_once 'vendor/autoload.php';

use App\Database\DatabaseFactory;
use App\Repository\ItemRepository;
use App\Parser\XMLParser;
use App\DataHandler\DataInserter;
use App\Logger\ErrorLogger;

try {
    $databaseType = $argv[1];
    $database = DatabaseFactory::create($databaseType);
    $pdo = $database->getConnection();

    $xmlFile = 'feed.xml';
    $xml = XMLParser::parse($xmlFile);

    if ($xml) {
        $itemRepository = new ItemRepository($pdo);
        $dataInserter = new DataInserter($itemRepository);
        $dataInserter->insertData($xml);
    }
    echo "Data processing complete.\n";
} catch (Exception $e) {
    ErrorLogger::logError($e->getMessage());
    echo "An error occurred. Check the error log for details.\n";
}
?>

