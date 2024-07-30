<?php
namespace App\Parser;

use XMLReader;
use App\Logger\ErrorLogger;

class XMLStreamParser {
    public static function parse(string $xmlFile, callable $callback): void {
        if (!file_exists($xmlFile)) {
            ErrorLogger::logError("XML file not found: $xmlFile");
            return;
        }

        $reader = new XMLReader();
        $reader->open($xmlFile);

        while ($reader->read()) {
            if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'item') {
                $outerXml = $reader->readOuterXML();
                if (!$outerXml) {
                    ErrorLogger::logError("Failed to read outer XML or outer XML is empty.");
                    continue;
                }

                $node = simplexml_load_string($outerXml);
                if ($node === false) {
                    ErrorLogger::logError("Failed to parse XML node: " . htmlspecialchars($outerXml));
                    continue;
                }
                $callback($node);
            }
        }
        $reader->close();
    }
}
?>
