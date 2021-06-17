<?php

namespace App\Http\Requests;

use App\Http\Controllers\WebQuoteController;
use App\Rules\EInvoiceCodeIt;
use App\Rules\FiscalOrVatNumberIt;
use App\Rules\VatNumberIt;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Sentry\State\Scope;

class WebQuoteRequest extends FormRequest {

    public function authorize() {
        return true;
    }

//    public function getRedirectUrl() {
//        return config('web_quote.error');
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {

        return [
            'quote_type'                 => 'required|in:' . WebQuoteController::QUOTE_TYPE_BUY . ',' . WebQuoteController::QUOTE_TYPE_LEASE,
            'm1_quantity'                => 'required|integer|min:1|max:20',
            'software_package'           => 'required|in:' . WebQuoteController::SOFTWARE_PACKAGE_PRIME . ',' . WebQuoteController::SOFTWARE_PACKAGE_HNB,
            'setup_gratis'               => 'required|in:true,false',
            'usage_place'                => 'required|in:' . WebQuoteController::USAGE_PLACE_INSIDE . ',' . WebQuoteController::USAGE_PLACE_OUTSIDE,
            'card_reader_quantity'       => 'nullable|integer|min:1|max:20',
            'card_reader_plan'           => 'nullable|required_with:card_reader_quantity|in:' . WebQuoteController::CARD_READER_PLAN_STANDARD . ',' . WebQuoteController::CARD_READER_PLAN_GOLD . ',' . WebQuoteController::CARD_READER_PLAN_PLATINUM,
            'first_name'                 => 'required|string|max:60',
            'last_name'                  => 'required|string|max:60',
            'company'                    => 'required|string|max:60',
            'vat_number'                 => [
                'required',
                new VatNumberIt
            ],
            'fiscal_number'              => [
                'required',
                new FiscalOrVatNumberIt
            ],
            'phone'                      => 'required|string|max:60',
            'email'                      => 'required|email',
            'address'                    => 'required|string|max:60',
            'zip'                        => [
                'required',
                'regex:/^[0-9]{5}$/i'
            ],
            'city'                       => 'required|string|max:60',
            'e_invoice_code'             => [
                'required',
                new EInvoiceCodeIt
            ],
            'accept_terms'               => 'required|accepted',
            'newsletter'                 => 'required|in:true,false',
            'sconto'                     => 'nullable|in:true,false',
            'different_shipping_address' => 'required|in:true,false',
            'shipping_address'           => 'nullable|required_if:different_shipping_address,true|string|max:60',
            'shipping_zip'               => [
                'nullable',
                'regex:/^[0-9]{5}$/i'
            ],
            'shipping_city'              => 'nullable|string|max:60',
            'shipping_notes'             => 'nullable|string|max:100',
        ];
    }

    public function failedValidation(Validator $validator) {

        if(app()->bound('sentry') && config('sentry.dsn') !== null) {

            app('sentry')->configureScope(function(Scope $scope) use ($validator): void {
                $scope->setExtras($validator->errors()->messages());
            });
        }
        Log::error('Web quote request validation error', $validator->errors()->messages());

        parent::failedValidation($validator);

    }
}
