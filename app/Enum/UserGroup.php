<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 * @method static static Level1()
 * @method static static Dev()
 * @method static static Billing()
 * @method static static Other()
 */
final class UserGroup extends Enum
{
    const Level1 =   'level1';
    const Dev =   'dev';
    const Billing = 'billing';
    const Other = 'other';
}
