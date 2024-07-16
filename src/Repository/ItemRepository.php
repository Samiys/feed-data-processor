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

    public function saveItem($item): bool
    {
        $sql = "INSERT INTO items (entity_id, category_name, sku, name, description, short_desc, price, link, image, brand, rating, caffeine_type, count, flavored, seasonal, in_stock, facebook, is_kcup)
                VALUES (:entity_id, :category_name, :sku, :name, :description, :short_desc, :price, :link, :image, :brand, :rating, :caffeine_type, :count, :flavored, :seasonal, :in_stock, :facebook, :is_kcup)";

        try {
            $data = $this->prepareItemForInsert($item);
            return $this->db->prepare($sql)->execute($data);
        } catch (PDOException $e) {
            throw new DatabaseException("Database error: " . $e->getMessage());
        }
    }

    private function prepareItemForInsert($item): array
    {
        return [
            'entity_id' => (int)$item->entity_id,
            'category_name' => (string)$item->category_name,
            'sku' => (string)$item->sku,
            'name' => (string)$item->name,
            'description' => (string)$item->description,
            'short_desc' => (string)$item->short_desc,
            'price' => (float)$item->price,
            'link' => (string)$item->link,
            'image' => (string)$item->image,
            'brand' => (string)$item->brand,
            'rating' => (int)$item->rating,
            'caffeine_type' => (string)$item->caffeine_type,
            'count' => (int)$item->count,
            'flavored' => (string)$item->flavored,
            'seasonal' => (string)$item->seasonal,
            'in_stock' => (string)$item->in_stock,
            'facebook' => (int)$item->facebook,
            'is_kcup' => (int)$item->is_kcup,
        ];
    }
}
?>
