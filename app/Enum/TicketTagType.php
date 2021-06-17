<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 * Class TicketTagType
 *
 * @package App\Enum
 *
 * @method static static NEW()
 * @method static static RESOLVED()
 */
class TicketTagType extends Enum
{
    public const NEW = 'new_ticket';
    public const RESOLVED = 'resolved_ticket';
}
