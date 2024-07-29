<?php

require_once 'vendor/autoload.php';

use App\Database\DatabaseDTO;
use App\Database\DatabaseFactory;
use App\DataHandler\DataInserter;
use App\Parser\XMLStreamParser;
use App\Repository\ItemRepository;
use App\Logger\ErrorLogger;

try {
    $file = 'feed.xml';
    $databaseType = $argv[1];
    $configArray = require __DIR__ . '/../config/config.php';
    $config = new DatabaseDTO($configArray);

    $databaseFactory = new DatabaseFactory($config);
    $database = $databaseFactory->create($databaseType);
    $pdo = $database->getConnection();

    $itemRepository = new ItemRepository($pdo);

    $dataInserter = new DataInserter($itemRepository);
    XMLStreamParser::parse($file, function ($item) use ($dataInserter) {
        try {
            $dataInserter->insertData($item);
        } catch (\Exception $e) {
            ErrorLogger::logError("Error processing item: " . $e->getMessage());
        }
    });

    echo "Data processing complete.\n";
} catch (Exception $e) {
    ErrorLogger::logError($e->getMessage());
    echo "An error occurred. Check the error log for details.\n";
}
?>
