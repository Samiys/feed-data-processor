<?php
namespace App\Parser;

use XMLReader;
use App\Logger\ErrorLogger;

class XMLStreamParser {
    /**
     * Parses an XML file and processes each 'item' node with the given callback.
     *
     * @param string $xmlFile The path to the XML file.
     * @param callable $callback A callback function to process each 'item' node.
     * @return void
     */
    public static function parse(string $xmlFile, callable $callback): void {
        if (!file_exists($xmlFile)) {
            ErrorLogger::logError("XML file not found: $xmlFile");
            return;
        }

        $reader = new XMLReader();
        $reader->open($xmlFile);

        while ($reader->read()) {
            if ($reader->nodeType === XMLReader::ELEMENT && $reader->localName === 'item') {
                $outerXml = $reader->readOuterXML();
                if ($outerXml === false) {
                    ErrorLogger::logError("Failed to read outer XML or outer XML is empty.");
                    continue;
                }

                // Load the XML node
                $node = simplexml_load_string($outerXml);
                if ($node === false) {
                    $error = libxml_get_errors();
                    $errorMsg = !empty($error) ? $error[0]->message : 'Unknown error';
                    ErrorLogger::logError("Failed to parse XML node: $errorMsg. XML: " . htmlspecialchars($outerXml));
                    libxml_clear_errors();
                    continue;
                }
                $callback($node);
            }
        }

        $reader->close();
    }
}
?>
