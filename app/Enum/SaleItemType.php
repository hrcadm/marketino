<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

class SaleItemType extends Enum
{

    public const PHYSICAL_PRODUCT = 'PHYSICAL_PRODUCT';
    public const DIGITAL_PRODUCT = 'DIGITAL_PRODUCT';
    public const SERVICE = 'SERVICE';
}
