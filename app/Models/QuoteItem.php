<?php

namespace App\Models;

use App\Enum\SaleVat;
use App\Models\Traits\DoNotAllowUpdate;
use App\Utils\VatHelper;

class QuoteItem extends MarketinoModel
{
    use DoNotAllowUpdate;

    protected $fillable = [
        'quote_id',
        'sale_item_price_id',
        'quantity',
        'total_discount_amount',
        'total_net_amount_before_discount',
        'total_net_amount',
        'note'
    ];

    public function saleItemPrice()
    {
        return $this->belongsTo(SaleItemPrice::class);
    }

    public static function makeItem(SaleItemPrice $saleItemPrice, int $quantity, string $note = null): QuoteItem
    {

        $totalDiscountAmount = $saleItemPrice->discount_amount ? $saleItemPrice->discount_amount * $quantity : 0;
        $totalNetAmountBeforeDiscount = ($saleItemPrice->net_price * $quantity);
        $totalNetAmount = $totalNetAmountBeforeDiscount - $totalDiscountAmount;

        return new QuoteItem([
            'sale_item_price_id' => $saleItemPrice->id,
            'quantity'           => $quantity,
            'note' => $note,

            'total_discount_amount'            => $totalDiscountAmount,
            'total_net_amount_before_discount' => $totalNetAmountBeforeDiscount,
            'total_net_amount'                 => $totalNetAmount,
        ]);
    }

    public function getTotalAmount():float{//with IVA

        $this->load('saleItemPrice.saleItem');
        $vatKey = $this->saleItemPrice->saleItem->vat->key;

        return VatHelper::getAmountWithVat($this->total_net_amount, SaleVat::fromKey($vatKey));
    }
}
