<?php


namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 * Class Quote Status
 *
 * @package App\Enum
 *
 * @method static static ACTIVE()
 * @method static static INACTIVE()
 * @method static static PAID()
 */
class QuoteStatus extends Enum
{
    public const ACTIVE = 'a';
    public const INACTIVE = 'i';
    public const PAID = 'p';
}
