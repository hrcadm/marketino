<?php

namespace App\Utils;

use App\Models\Quote;

class PdfQuote
{
    private $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function getCustomerDataBlockAttribute(): string
    {

        $lines = [];

        $lines[] = $this->quote->customer_data->first_name . ' ' . $this->quote->customer_data->last_name;

        $lines[] = $this->quote->customer_data->company_name;

        if ($this->quote->customer_data->vat_number) {
            $lines[] .= 'P. IVA: ' . $this->quote->customer_data->vat_number;
        }

        $lines[] = $this->quote->customer_data->address;
        $lines[] = $this->quote->customer_data->address_additional;

        $lines[] = $this->quote->customer_data->zip . ' ' . $this->quote->customer_data->city;
        $lines[] = $this->quote->customer_data->region . ' ' . $this->quote->customer_data->country_code;

        $lines = array_map('trim', $lines); //trim all lines

        $lines = array_filter($lines); //remove empty lines

        return implode("<br />", $lines);
    }
}
