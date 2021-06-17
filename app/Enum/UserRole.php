<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 * Class UserRole
 *
 * @package App\Enum
 *
 * @method static static PARTNER()
 * @method static static AGENT()
 * @method static static SUPERADMIN()
 * @method static static STUDENT()
 */
class UserRole extends Enum
{
    public const PARTNER = 'partner';
    public const AGENT = 'agent';
    public const SUPERADMIN = 'superadmin';
    public const STUDENT = 'student';
}
