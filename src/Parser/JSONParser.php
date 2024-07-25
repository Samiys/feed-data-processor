<?php

namespace App\Parser;

use App\Logger\ErrorLogger;

class JSONParser {
    /**
     * Parses a JSON file and returns the data as an array.
     *
     * @param string $jsonFile The path to the JSON file.
     * @return array|null The parsed data or null on failure.
     */
    public static function parse(string $jsonFile): ?array {
        if (!file_exists($jsonFile)) {
            ErrorLogger::logError("JSON file not found: $jsonFile");
            return null;
        }

        $content = file_get_contents($jsonFile);
        if (!$content) {
            ErrorLogger::logError("Failed to read JSON file: $jsonFile");
            return null;
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            ErrorLogger::logError("Failed to decode JSON content: " . json_last_error_msg());
            return null;
        }

        return $data;
    }
}
