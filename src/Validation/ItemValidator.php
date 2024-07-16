<?php
namespace App\Validation;

use App\Exceptions\InvalidEntityException;
use App\Exceptions\MissingFieldException;
use App\Logger\ErrorLogger;

class ItemValidator implements ValidatorInterface {
    public static function validate($data): bool {
        $entityId = (string) $data->entity_id;
        if (empty($entityId) || !is_numeric($entityId)) {
            ErrorLogger::logError("Validation failed for entity_id: " . $entityId . ". Invalid entity_id.");
            throw new InvalidEntityException("Invalid entity_id: $entityId");
        }

        $requiredFields = ['sku', 'name', 'price'];
        foreach ($requiredFields as $field) {
            if (empty($data->$field)) {
                ErrorLogger::logError("Validation failed for entity_id: " . $entityId . ". Missing {$field}.");
                throw new MissingFieldException("Missing required field: $field for entity_id: $entityId");
            }
        }

        return true;
    }
}
?>
