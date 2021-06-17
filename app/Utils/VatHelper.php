<?php


namespace App\Utils;


use App\Enum\SaleVat;

class VatHelper {

    public static function getAmountWithVat(float $netAmonut, SaleVat $vat):float{
        return $netAmonut * (1 + ($vat->toFloat() / 100));
    }

}
