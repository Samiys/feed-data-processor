<?php

namespace App\Parser;

use App\Logger\ErrorLogger;

class JSONParser {
    /**
     * Parses a JSON file and processes each item with the given callback.
     *
     * @param string $jsonFile The path to the JSON file.
     * @param callable $callback A callback function to process each item.
     * @return void
     */
    public static function parse(string $jsonFile, callable $callback): void {
        if (!file_exists($jsonFile)) {
            ErrorLogger::logError("JSON file not found: $jsonFile");
            return;
        }

        $jsonData = file_get_contents($jsonFile);
        if ($jsonData === false) {
            ErrorLogger::logError("Failed to read JSON file: $jsonFile");
            return;
        }

        $data = json_decode($jsonData, true);
        if ($data === null) {
            ErrorLogger::logError("Failed to parse JSON data");
            return;
        }

        foreach ($data as $item) {
            try {
                $callback($item);
            } catch (\Exception $e) {
                ErrorLogger::logError("Error processing item: " . $e->getMessage());
            }
        }
    }
}
