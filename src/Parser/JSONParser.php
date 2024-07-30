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
        if ($content === false) {
            ErrorLogger::logError("Failed to read JSON file: $jsonFile");
            return null;
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            ErrorLogger::logError("Failed to decode JSON content: " . json_last_error_msg());
            return null;
        }

        if (!is_array($data)) {
            ErrorLogger::logError("JSON content is not a valid array.");
            return null;
        }

        foreach ($data as &$item) {
            if (isset($item['price']) && is_string($item['price'])) {
                $item['price'] = floatval($item['price']);
            }

            if (isset($item['facebook']) && is_string($item['facebook'])) {
                $item['facebook'] = (int)$item['facebook'];
            }
            if (isset($item['is_kcup']) && is_string($item['is_kcup'])) {
                $item['is_kcup'] = (int)$item['is_kcup'];
            }
        }

        return $data;
    }
}
