<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

class CustomerStatus extends Enum
{
    public const LEAD = 'LEAD';
    public const OPPORTUNITY = 'OPPORTUNITY';
    public const ACCOUNT = 'ACCOUNT';
    public const OPPORTUNITY_LOST = 'OPPORTUNITY_LOST';
}
