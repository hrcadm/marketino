<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FiscalNumberIt implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->isValidFiscalCode($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be valid italian fiscal number (Codice Fiscale).';
    }

    /**
     * http://www.icosaedro.it/cf-pi/cf-php.txt
     */
    private function isValidFiscalCode($fiscalCode): bool
    {
        //codice fiscale
        if (!$fiscalCode || !is_string($fiscalCode) || strlen($fiscalCode) !== 16) {
            return false;
        }

        //$cf = strtoupper($cf);
        if (preg_match('/^[A-Z0-9]+$/D', $fiscalCode) !== 1) {
            return false;
        }

        $s = 0;
        for ($i = 1; $i <= 13; $i += 2) {
            $c = $fiscalCode[$i];
            if (strcmp($c, '0') >= 0 && strcmp($c, '9') <= 0) {
                $s += ord($c) - ord('0');
            } else {
                $s += ord($c) - ord('A');
            }
        }

        for ($i = 0; $i <= 14; $i += 2) {
            $c = $fiscalCode[$i];
            switch ($c) {
                case 'A':
                case '0':
                    ++$s;
                    break;
                case 'B':
                case '1':
                    $s += 0;
                    break;
                case 'C':
                case '2':
                    $s += 5;
                    break;
                case 'D':
                case '3':
                    $s += 7;
                    break;
                case 'E':
                case '4':
                    $s += 9;
                    break;
                case 'F':
                case '5':
                    $s += 13;
                    break;
                case 'G':
                case '6':
                    $s += 15;
                    break;
                case 'H':
                case '7':
                    $s += 17;
                    break;
                case 'I':
                case '8':
                    $s += 19;
                    break;
                case 'J':
                case '9':
                    $s += 21;
                    break;
                case 'K':
                    $s += 2;
                    break;
                case 'L':
                    $s += 4;
                    break;
                case 'M':
                    $s += 18;
                    break;
                case 'N':
                    $s += 20;
                    break;
                case 'O':
                    $s += 11;
                    break;
                case 'P':
                    $s += 3;
                    break;
                case 'Q':
                    $s += 6;
                    break;
                case 'R':
                    $s += 8;
                    break;
                case 'S':
                    $s += 12;
                    break;
                case 'T':
                    $s += 14;
                    break;
                case 'U':
                    $s += 16;
                    break;
                case 'V':
                    $s += 10;
                    break;
                case 'W':
                    $s += 22;
                    break;
                case 'X':
                    $s += 25;
                    break;
                case 'Y':
                    $s += 24;
                    break;
                case 'Z':
                    $s += 23;
                    break;
                default:
                    return false;
            }
        }

        return chr($s % 26 + ord('A')) === $fiscalCode[15];
    }
}
