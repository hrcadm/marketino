<?php

namespace App\Http\Requests;

use App\Enum\CompanyType;
use App\Enum\CountryCode;
use App\Enum\CustomerStatus;
use App\Enum\CustomerStatusEnum;
use App\Enum\SaleChannel;
use App\Rules\EInvoiceCodeIt;
use App\Rules\FiscalOrVatNumberIt;
use App\Rules\VatNumberIt;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules() {

        return [

            'first_name'         => 'required|string',
            'last_name'          => 'required|string',
            'company_name'       => 'required|string',
            'vat_number'         => [
                'required',
                new VatNumberIt
            ],
            'fiscal_number'      => [
                'nullable',
                new FiscalOrVatNumberIt
            ],
            'address'            => 'nullable|string',
            'address_additional' => 'nullable|string',
            'zip'                => 'nullable|string|size:5',
            'city'               => 'nullable|string',
            'region'             => 'nullable|string',
            'country_code'       => 'sometimes|enum_value:' . CountryCode::class,
            'note'               => 'nullable|string|max:255',
            'gdpr'               => 'nullable|in:on,1',
            'newsletter'         => 'nullable|in:on,1',
            'source'             => 'sometimes|enum_value:' . SaleChannel::class,
            'activity_type_id'   => 'nullable|uuid',
            'customer_status'    => 'sometimes|enum_value:' . CustomerStatus::class,
            'einvoice_code'      => [
                'nullable',
                new EInvoiceCodeIt
            ],
            'iban'               => 'nullable|string',
            'iban_name'          => 'nullable|string',
            'company_type'       => 'sometimes|enum_value:' . CompanyType::class,
            'company_date'       => 'nullable|date|date_format:Y-m-d|before_or_equal:today',
            'legal_contact'      => 'nullable|string',
        ];
    }

}
