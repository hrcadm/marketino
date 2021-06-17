<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Validator;

class EInvoiceCodeIt implements Rule
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
        /**
         * if SDI = 7 alphanumeric, if PEC = email format
         */

        $validator = Validator::make(['email' => $value], [
            'email' => 'email'
        ]);

        if ($validator->passes()) {
            return true;
        }

        if (strlen($value) === 7) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be valid italian e-invoice code (PEC or SDI).';
    }
}
