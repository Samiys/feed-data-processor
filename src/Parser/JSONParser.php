<?php

namespace App\Parser;

use App\Logger\ErrorLogger;

class JSONParser {
    /**
     * Parses a large JSON file and processes each item with the given callback.
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

        $handle = fopen($jsonFile, 'r');
        if ($handle === false) {
            ErrorLogger::logError("Failed to open JSON file: $jsonFile");
            return;
        }

        $buffer = '';
        $inString = false;

        while (($char = fgetc($handle)) !== false) {
            $buffer .= $char;

            if ($char === '"') {
                $inString = !$inString;
            }

            if (!$inString && ($char === '}' || $char === ']')) {
                $data = json_decode($buffer, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                    foreach ($data as $item) {
                        try {
                            $callback($item);
                        } catch (\Exception $e) {
                            ErrorLogger::logError("Error processing item: " . $e->getMessage());
                        }
                    }
                    $buffer = '';
                } else {
                    ErrorLogger::logError("JSON decode error: " . json_last_error_msg());
                }
            }
        }

        if (!empty($buffer)) {
            $data = json_decode($buffer, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                foreach ($data as $item) {
                    try {
                        $callback($item);
                    } catch (\Exception $e) {
                        ErrorLogger::logError("Error processing item: " . $e->getMessage());
                    }
                }
            } else {
                ErrorLogger::logError("Failed to parse remaining JSON data");
            }
        }

        fclose($handle);
    }
}
