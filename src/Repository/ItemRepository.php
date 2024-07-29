<?php

namespace App\Repository;

use App\Exceptions\DatabaseException;
use PDO;
use PDOException;

class ItemRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function saveItem(array $item): bool
    {
        $sql = "INSERT INTO items (
                entity_id, category_name, sku, name, description, short_desc, 
                price, link, image, brand, rating, caffeine_type, count, 
                flavored, seasonal, in_stock, facebook, is_kcup
            ) VALUES (
                :entity_id, :category_name, :sku, :name, :description, :short_desc, 
                :price, :link, :image, :brand, :rating, :caffeine_type, :count, 
                :flavored, :seasonal, :in_stock, :facebook, :is_kcup
            )";

        try {
            $data = $this->prepareItemForInsert($item);
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($data);
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                echo "SQLSTATE: " . $errorInfo[0] . "\n";
                echo "Driver error code: " . $errorInfo[1] . "\n";
                echo "Driver error message: " . $errorInfo[2] . "\n";
            }
            return $result;
        } catch (PDOException $e) {
            throw new DatabaseException("Database error: " . $e->getMessage());
        }
    }

    private function prepareItemForInsert(array $item): array
    {
        return [
            ':entity_id'     => $item['entity_id'],
            ':category_name' => $item['CategoryName'],
            ':sku'           => $item['sku'],
            ':name'          => $item['name'],
            ':description'   => $item['description'],
            ':short_desc'    => $item['shortdesc'],
            ':price'         => $item['price'],
            ':link'          => $item['link'],
            ':image'         => $item['image'],
            ':brand'         => $item['Brand'],
            ':rating'        => (int)$item['Rating'],
            ':caffeine_type' => $item['CaffeineType'],
            ':count'         => (int)$item['Count'],
            ':flavored'      => $item['Flavored'],
            ':seasonal'      => $item['Seasonal'],
            ':in_stock'      => $item['Instock'],
            ':facebook'      => $item['Facebook'],
            ':is_kcup'       => $item['IsKCup']
        ];
    }
}
?>
