<?php
namespace App\Validation;

interface ValidatorInterface {
    public static function validate($data): bool;
}
?>
