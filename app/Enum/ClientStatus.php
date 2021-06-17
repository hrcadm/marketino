<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 * Class ClientStatus
 *
 * @package App\Enum
 *
 * @method static static LEAD()
 * @method static static OPPORTUNITY()
 * @method static static ACCOUNT()
 * @method static static OPPORTUNITY_LOST()
 */
class ClientStatus extends Enum
{
    public const LEAD = 'LEAD';
    public const OPPORTUNITY = 'OPPORTUNITY';
    public const ACCOUNT = 'ACCOUNT';
    public const OPPORTUNITY_LOST = 'OPPORTUNITY_LOST';
}
