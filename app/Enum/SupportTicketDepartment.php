<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 * Class SupportTicketDepartment
 *
 * @package App\Enum
 *
 * @method static static TECHNICAL_LEVEL_1()
 * @method static static GENERAL()
 * @method static static BILLING()
 * @method static static DEV()
 */
class SupportTicketDepartment extends Enum
{
    public const TECHNICAL_LEVEL_1 = 'level_1';
    public const GENERAL = 'general';
    public const BILLING = 'billing';
    public const DEV = 'dev';
}
