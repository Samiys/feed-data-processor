<?php
namespace App\Parser;

use App\Logger\ErrorLogger;
use SimpleXMLElement;

class XMLParser {
    public static function parse(string $xmlFile): ?SimpleXMLElement {
        if (!file_exists($xmlFile)) {
            ErrorLogger::logError("XML file not found: $xmlFile");
            return null;
        }

        $content = file_get_contents($xmlFile);
        if (!$content) {
            ErrorLogger::logError("Failed to read XML file: $xmlFile");
            return null;
        }

        $xml = simplexml_load_string($content);
        if (!$xml) {
            ErrorLogger::logError("Failed to load XML content: $xmlFile");
            return null;
        }

        return $xml;
    }
}
?>
