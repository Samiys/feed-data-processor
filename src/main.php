<?php

require_once 'vendor/autoload.php';

use App\Database\DatabaseDTO;
use App\Database\DatabaseFactory;
use App\Parser\JSONParser;
use App\Parser\XMLStreamParser;
use App\Repository\ItemRepository;
use App\DataHandler\DataInserter;
use App\Logger\ErrorLogger;


try {
    $databaseType = $argv[1];
    $config = new DatabaseDTO(require __DIR__ . '/../../config/config.php');
    $databaseFactory = new DatabaseFactory($config);
    $database = $databaseFactory->create($databaseType);
    $pdo = $database->getConnection();

    $file = 'feed.xml';
    $itemRepository = new ItemRepository($pdo);
    $dataInserter = new DataInserter($itemRepository);

    if (pathinfo($file, PATHINFO_EXTENSION) === 'xml') {
        XMLStreamParser::parse($file, function ($item) use ($dataInserter) {
            $dataInserter->insertData([$item]);
        });
    } elseif (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
        $data = JSONParser::parse($file);
        if ($data) {
            $dataInserter->insertData($data);
        }
    }

    echo "Data processing complete.\n";
} catch (Exception $e) {
    ErrorLogger::logError($e->getMessage());
    echo "An error occurred. Check the error log for details.\n";
}
?>

