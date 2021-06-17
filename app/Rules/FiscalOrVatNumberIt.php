<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FiscalOrVatNumberIt implements Rule
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
        return (new VatNumberIt)->passes($attribute, $value) || (new FiscalNumberIt)->passes($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be valid italian fiscal number (Codice Fiscale) or vat number (Partita IVA).';
    }
}
