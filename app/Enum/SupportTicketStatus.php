<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 * Class SupportTicketStatus
 *
 * @package App\Enum
 *
 * @method static static OPEN()
 * @method static static CLOSED()
 */
class SupportTicketStatus extends Enum
{
    public const OPEN = 'open';
    public const CLOSED = 'closed';
}
