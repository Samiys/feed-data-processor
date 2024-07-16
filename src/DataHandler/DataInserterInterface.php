<?php
namespace App\DataHandler;

interface DataInserterInterface {
    public function insertData($xml): void;
}
?>
