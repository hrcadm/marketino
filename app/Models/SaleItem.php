<?php

namespace App\Models;

use App\Enum\SaleItemType;
use App\Enum\SaleVat;
use App\Models\Traits\DoNotAllowUpdate;
use App\Rules\DecimalNumber;
use Validator;

class SaleItem extends MarketinoModel
{
    use DoNotAllowUpdate;

    protected $fillable = [
        'name',
        'vat',
        'type',
//        'weight',
//        'dimensions',
    ];

    protected $casts = [
        'vat'  => SaleVat::class,
        'type' => SaleItemType::class,
    ];

    public function prices()
    {
        return $this->hasMany(SaleItemPrice::class);
    }

    public function getValidator(): \Illuminate\Contracts\Validation\Validator
    {

        return Validator::make($this->attributesToArray(), [
            'name'  => 'required|max:60',
            'vat'   => 'required|enum_value:' . SaleVat::class,
            'type'  => 'required|enum_value:' . SaleItemType::class,
        ]);
    }
}
