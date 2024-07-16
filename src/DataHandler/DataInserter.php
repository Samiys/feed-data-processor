<?php
namespace App\DataHandler;

use App\Exceptions\DatabaseException;
use App\Exceptions\InvalidEntityException;
use App\Exceptions\MissingFieldException;
use App\Logger\ErrorLogger;
use App\Repository\ItemRepository;
use App\Validation\ItemValidator;

class DataInserter implements DataInserterInterface {
    private $itemRepository;

    public function __construct(ItemRepository $itemRepository) {
        $this->itemRepository = $itemRepository;
    }

    public function insertData($xml): void {
        foreach ($xml->item as $item) {
            try {
                if (ItemValidator::validate($item)) {
                    $this->itemRepository->saveItem($item);
                }
            } catch (InvalidEntityException | MissingFieldException $e) {
                ErrorLogger::logError($e->getMessage(), ['item' => (array) $item]);
            } catch (DatabaseException $e) {
                ErrorLogger::logError($e->getMessage());
            }
        }
    }
}
?>

