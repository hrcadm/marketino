<?php


namespace App\Http\Requests;


use App\Models\SaleItemPrice;
use Illuminate\Foundation\Http\FormRequest;

class QuoteItemRequest extends FormRequest {

    public function rules() {

        return [
            ' marketinoOne.' . SaleItemPrice::M1_PROMO  => 'nullable | numeric|min:1|max:20',
            ' marketinoOne.' . SaleItemPrice::M1_FINALE => 'nullable | numeric|min:1|max:20',
            ' marketinoOne.' . SaleItemPrice::M1_ROTAM  => 'nullable | numeric|min:1|max:20',

            'cardReader.' . SaleItemPrice::CR_PROMO => 'nullable|numeric|min:1|max:20',
            'shipment' . SaleItemPrice::SHIPMENT    => 'nullable|numeric|max:1',
            'setup.' . SaleItemPrice::SETUP_PROMO   => 'nullable|numeric|max:1'
        ];
    }
}
