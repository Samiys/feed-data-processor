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
        $dataArray = $this->xmlToArray($xml);
        $products = $this->processProductData($dataArray);
        foreach ($products as $product) {
            try {
                if (ItemValidator::validate($product)) {
                    $this->itemRepository->saveItem($product);
                }
            } catch (InvalidEntityException | MissingFieldException $e) {
                ErrorLogger::logError($e->getMessage(), ['item' => $product]);
            } catch (DatabaseException $e) {
                ErrorLogger::logError($e->getMessage());
            }
        }
    }

    private function processProductData($dataArray): array
    {
        $products = [];
        $productKeys = [
            'entity_id', 'CategoryName', 'sku', 'name', 'description',
            'shortdesc', 'price', 'link', 'image', 'Brand', 'Rating',
            'CaffeineType', 'Count', 'Flavored', 'Seasonal', 'Instock',
            'Facebook', 'IsKCup'
        ];

        while ($dataArray) {
            $product = [];
            foreach ($productKeys as $iValue) {
                $product[$iValue] = trim(array_shift($dataArray));
            }
            $products[] = $product;
        }

        return $products;
    }

    // Function to convert SimpleXMLElement to an array with proper handling of CDATA and empty elements
    private function xmlToArray($xmlObject) {
        $array = $this->convertXmlToArray($xmlObject);
        return $this->sanitizeArray($array);
    }

    // Helper function to convert XML object to array
    private function convertXmlToArray($xml): array
    {
        $result = [];
        foreach ((array)$xml as $key => $value) {
            if (is_object($value) && $value instanceof SimpleXMLElement) {
                $result[$key] = $this->convertXmlToArray($value);
            } else {
                $result[$key] = (string)$value;
            }
        }
        return $result;
    }

    // Recursive function to sanitize the array
    private function sanitizeArray($array) {
        foreach ($array as $key => $value) {
            if (is_array($value) && empty($value)) {
                $array[$key] = '';
            } elseif (is_array($value)) {
                $array[$key] = $this->sanitizeArray($value);
            }
        }
        return $array;
    }
}
?>
