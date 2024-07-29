<?php
namespace App\DataHandler;

use SimpleXMLElement;

interface DataInserterInterface {
    public function insertData(SimpleXMLElement $xml): void;
}
?>
