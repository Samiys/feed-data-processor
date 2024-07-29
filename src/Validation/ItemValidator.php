<?php
namespace App\Validation;

use App\Exceptions\InvalidEntityException;
use App\Exceptions\MissingFieldException;
use App\Logger\ErrorLogger;

class ItemValidator implements ValidatorInterface {

    /**
     * @throws InvalidEntityException
     * @throws MissingFieldException
     */
    public static function validate(array $data): bool {
        $entityId = $data['entity_id'] ?? '';
        self::validateEntityId($entityId);

        $requiredFields = ['sku', 'name', 'price'];
        foreach ($requiredFields as $field) {
            self::validateRequiredField($data[$field] ?? '', $field, $entityId);
        }

        return true;
    }

    /**
     * @throws InvalidEntityException
     */
    private static function validateEntityId(string $entityId): void {
        if (empty($entityId) || !is_numeric($entityId)) {
            ErrorLogger::logError("Validation failed for entity_id: " . $entityId . ". Invalid entity_id.");
            throw new InvalidEntityException("Invalid entity_id: $entityId");
        }
    }

    /**
     * @throws MissingFieldException
     */
    private static function validateRequiredField($value, string $fieldName, string $entityId): void {
        if (empty($value)) {
            ErrorLogger::logError("Validation failed for entity_id: " . $entityId . ". Missing $fieldName.");
            throw new MissingFieldException("Missing required field: $fieldName for entity_id: $entityId");
        }
    }
}
?>
