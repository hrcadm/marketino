<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 * Class TicketMessageType
 *
 * @package App\Enum
 *
 * @method static static INBOUND()
 * @method static static OUTBOUND()
 */
class TicketMessageType extends Enum
{
    public const INBOUND = 'inbound';
    public const OUTBOUND = 'outbound';
}
