<?php

/** @noinspection SpellCheckingInspection */

namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 * Class CompanyType
 *
 * @package App\Enum
 *
 * @method static static INDIVIDUAL()
 * @method static static CAPITAL()
 * @method static static PERSON()
 * @method static static CONSORTIUM()
 * @method static static OTHER()
 */
class CompanyType extends Enum
{
    public const INDIVIDUAL = 'INDIVIDUAL';    //Ditta Individuale
    public const CAPITAL = 'CAPITAL';          //Società di Capitale
    public const PERSON = 'PERSON';            //Società di Person Associazioni
    public const CONSORTIUM = 'CONSORTIUM';    //Consorzio
    public const OTHER = 'OTHER';              //Altro
}
