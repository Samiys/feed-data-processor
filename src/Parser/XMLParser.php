<?php
namespace App\Parser;

use App\Logger\ErrorLogger;
use SimpleXMLElement;

class XMLParser {
    /**
     * Parses an XML file and returns the root SimpleXMLElement.
     *
     * @param string $xmlFile The path to the XML file.
     * @return SimpleXMLElement|null The parsed XML or null on failure.
     */
    public static function parse(string $xmlFile, callable $callback): void {
        if (!file_exists($xmlFile)) {
            ErrorLogger::logError("XML file not found: $xmlFile");
            return;
        }

        $reader = new XMLReader();
        $reader->open($xmlFile);

        // Loop through the XML file and process each <item> node
        while ($reader->read()) {
            if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'item') {
                try {
                    $node = new SimpleXMLElement($reader->readOuterXML());
                    $callback($node);
                } catch (\Exception $e) {
                    ErrorLogger::logError("Failed to parse XML node: " . $e->getMessage());
                }
            }
        }

        $reader->close();
    }
}
?>
