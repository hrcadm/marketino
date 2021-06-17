<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class VatNumberIt implements Rule {
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value) {
        return $this->isValidVATNumber($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return 'The :attribute must be valid italian vat number (Partita IVA).';
    }

    /**
     * http://www.icosaedro.it/cf-pi/pi-php.txt
     */
    private function isValidVATNumber($pi): bool {

        if(!$pi || !is_string($pi) || strlen($pi) !== 11) {
            return false;
        }

        if(preg_match('/^\d{11}$/D', $pi) !== 1) {
            return false;
        }

        $s = 0;
        for($i = 0; $i <= 9; $i += 2) {
            $s += ord($pi[$i]) - ord('0');
        }

        for($i = 1; $i <= 9; $i += 2) {
            $c = 2 * (ord($pi[$i]) - ord('0'));
            if($c > 9) {
                $c -= 9;
            }
            $s += $c;
        }

        return (10 - $s % 10) % 10 === ord($pi[10]) - ord('0');
    }
}
