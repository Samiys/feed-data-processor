<?php

require_once 'vendor/autoload.php';

use App\Database\DatabaseDTO;
use App\Database\DatabaseFactory;
use App\DataHandler\DataInserter;
use App\Parser\XMLStreamParser;
use App\Parser\JSONParser;
use App\Repository\ItemRepository;
use App\Logger\ErrorLogger;

try {
    $file = $argv[1];
    $databaseType = $argv[2];
    $configArray = require __DIR__ . '/../config/config.php';
    $config = new DatabaseDTO($configArray);

    $databaseFactory = new DatabaseFactory($config);
    $database = $databaseFactory->create($databaseType);
    $pdo = $database->getConnection();

    $itemRepository = new ItemRepository($pdo);
    $dataInserter = new DataInserter($itemRepository);

    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

    switch ($fileExtension) {
        case 'xml':
            XMLStreamParser::parse($file, function ($item) use ($dataInserter) {
                try {
                    $dataInserter->insertData($item);
                } catch (\Exception $e) {
                    ErrorLogger::logError("Error processing item: " . $e->getMessage());
                }
            });
            break;
        case 'json':
            $items = JSONParser::parse($file);
            if ($items) {
                foreach ($items as $item) {
                    try {
                        $dataInserter->insertData((object)$item);
                    } catch (\Exception $e) {
                        ErrorLogger::logError("Error processing item: " . $e->getMessage());
                    }
                }
            }
            break;
        default:
            throw new Exception("Unsupported file type: $fileExtension");
    }

    echo "Data processing complete.\n";
} catch (Exception $e) {
    ErrorLogger::logError($e->getMessage());
    echo "An error occurred. Check the error log for details.\n";
}
?>
