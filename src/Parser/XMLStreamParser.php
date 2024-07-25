<?php

namespace App\Parser;

use Exception;
use XMLReader;
use SimpleXMLElement;
use App\Logger\ErrorLogger;

class XMLStreamParser {
    /**
     * Parses an XML file using stream-based parsing and processes each item node using the provided callback function.
     *
     * @param string $xmlFile The path to the XML file.
     * @param callable $callback The callback function to process each item node.
     * @throws Exception
     */
    public static function parse(string $xmlFile, callable $callback): void {
        if (!file_exists($xmlFile)) {
            ErrorLogger::logError("XML file not found: $xmlFile");
            return;
        }

        $reader = new XMLReader();
        $reader->open($xmlFile);

        while ($reader->read()) {
            if ($reader->nodeType === XMLReader::ELEMENT && $reader->name === 'item') {
                $node = new SimpleXMLElement($reader->readOuterXML());
                $callback($node);
            }
        }

        $reader->close();
    }
}
