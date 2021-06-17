<?php

namespace App\Models;

use App\Models\Traits\DoNotAllowUpdate;
use App\Rules\DecimalNumber;
use Validator;

class SaleItemPrice extends MarketinoModel {
    use DoNotAllowUpdate;

    public const M1_PROMO = "M1-PROMO";
    public const M1_LIMITED_PROMO = "M1-LIMITED-PROMO";
    public const M1_FINALE = "M1-FINALE";
    public const M1_ROTAM = "M1-ROTAM";
    public const M1_RATE = "M1-RATE";
    public const CR_PROMO = "CR-PROMO";
    public const SETUP_PROMO = "SETUP-PROMO";
    public const SHIPMENT = "SHIPMENT";

    protected $fillable = [
        'sale_item_id',
        'is_default',
        'name',
        'note',
        //ispisuje se na ponudi (ako ga quote item note ne overrida)
        'net_price',
        'original_net_price',
        'discount_name',
        'discount_amount',
        'description',
        //ispisuje se kao opis na sučelju
        'short_id',
        '_data',
    ];
    protected $casts = [
        '_data' => 'object',
    ];

    public function saleItem() {
        return $this->belongsTo(SaleItem::class);
    }

    public function quoteItem() {
        return $this->hasMany(QuoteItem::class);
    }


    public function getValidator(): \Illuminate\Contracts\Validation\Validator {

        return Validator::make($this->attributesToArray(), [
            'sale_item_id'       => 'required|exists:' . ((new SaleItem())->getTable()) . ',id',
            'is_default'         => 'boolean',
            'name'               => 'required|max:60',
            'net_price'          => [
                'required',
                new DecimalNumber()
            ],
            'original_net_price' => [
                'nullable',
                new DecimalNumber()
            ],
            'discount_name'      => 'nullable|max:60',
            'discount_amount'    => [
                'nullable',
                new DecimalNumber()
            ]
        ]);
    }
}
