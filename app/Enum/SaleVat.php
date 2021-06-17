<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

class SaleVat extends Enum
{

    public const VAT_22 = '22';

    public function toFloat(): float
    {
        return (float) $this->value;
    }
}
