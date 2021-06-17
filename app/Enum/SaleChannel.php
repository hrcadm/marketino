<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 * Class SaleChannel
 *
 * @package App\Enum
 *
 * @method static static PHONE()
 * @method static static WEB()
 * @method static static EMAIL()
 * @method static static FACEBOOK()
 * @method static static INSTAGRAM()
 * @method static static RECOMMENDATION()
 * @method static static FAIR()
 * @method static static OTHER()
 * @method static static SPECIAL_ACTION()
 */
class SaleChannel extends Enum
{
    public const PHONE = 'PHONE';
    public const WEB = 'WEB';
    public const EMAIL = 'EMAIL';
    public const FACEBOOK = 'FACEBOOK';
    public const INSTAGRAM = 'INSTAGRAM';
    public const RECOMMENDATION = 'RECOMMENDATION';
    public const FAIR = 'FAIR';
    public const OTHER = 'OTHER';
    public const SPECIAL_ACTION = 'SPECIAL_ACTION';
}
