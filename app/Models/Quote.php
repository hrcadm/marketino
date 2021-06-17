<?php

namespace App\Models;

use App\Enum\SaleVat;
use App\Models\Traits\DoNotAllowUpdate;
use App\Utils\VatHelper;
use Auth;
use App\Enum;
use Exception;
use Illuminate\Support\Collection;
use RuntimeException;

/**
 * Class Quote
 * This model has QuoteObserver that generate control number for quote number
 */
class Quote extends MarketinoModel {

    protected $fillable = [
        'user_id',
        'customer_id',
        'customer_data',
        'number',
        'control_number',
        'total_amount',
        'total_net_amount',
        'total_vat_amount',
        'note',
    ];

    protected $casts = [
        'customer_data' => 'object',
        'valid_until'   => 'date',
        'status' => Enum\QuoteStatus::class
    ];

    public static function makeQuote(Customer $customer, Collection $quoteItems, string $note = null): Quote {

        $totalNetAmountsByVat = [];

        $quoteItems->each(function(QuoteItem $quoteItem) use (&$totalNetAmountsByVat) {

            //$saleItemPrice = $quoteItem->with('saleItemPrice.saleItem')->get();

            $quoteItem->load('saleItemPrice.saleItem');

           // $saleItem = $saleItemPrice->saleItem()->first();

            $saleItemVat = $quoteItem->saleItemPrice->saleItem->vat->key;

            if(!isset($totalNetAmountsByVat[$saleItemVat])) {
                $totalNetAmountsByVat[$saleItemVat] = 0;
            }

            $totalNetAmountsByVat[$saleItemVat] += $quoteItem->total_net_amount;
        });


        $totalNetAmount = 0;
        $totalVatAmount = 0;

        foreach($totalNetAmountsByVat as $vatKey => $netAmonut) {
            $totalNetAmount += $netAmonut;

           // $vatPercentage = (float)SaleVat::fromKey($vatKey)->value;
            //$totalAmountPerVat = $netAmonut * (1 + ($vatPercentage / 100));
            $totalAmountPerVat = VatHelper::getAmountWithVat($netAmonut, SaleVat::fromKey($vatKey));

            $totalVatAmount += $totalAmountPerVat - $netAmonut;
        }

        $userId = null;

        if(Auth::check()) {
            $userId = Auth::user()->id;
        }

        return new Quote([
            'customer_id'      => $customer->id,
            'customer_data'    => $customer->getContactData(),
            'total_amount'     => $totalNetAmount + $totalVatAmount,
            'total_net_amount' => $totalNetAmount,
            'total_vat_amount' => $totalVatAmount,
            'note'             => $note,
            'user_id'          => $userId
        ]);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getDocumentNumberAttribute(): string {
        return $this->number . $this->control_number;
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function calculateControlNumber(): int {
        if(!$this->number) {
            throw new Exception("Control number can not be calculated because number is null (model not saved?)");
        }

        $output = 0;
        $number = (string)$this->number;
        $mulTwo = true;
        for($index = strlen($number) - 1; $index >= 0; $index--) {
            $val = $mulTwo ? (int)$number[$index] * 2 : (int)$number[$index];
            if($val > 9) {
                $val = (string)$val;
                $val = (int)$val[0] + (int)$val[1];
            }
            $mulTwo = !$mulTwo;
            $output += (int)$val;
        }
        $output = 10 - ($output % 10);
        return $output < 10 ? $output : 0;
    }

    public function getPdfPath(): string {
        return 'quotes/' . $this->created_at->format('Y/m/') . $this->id . '.pdf';
    }

    public function getPdfFileName(): string {
        return __('mail.quote_attachment_name') . '_' . $this->document_number . '.pdf';
    }

    public function containsMarketinoCashRegisterWithoutRates(): bool {

        $items = $this->items()->whereHas('saleItemPrice', function($q) {
            $q->whereIn('short_id', [
                'M1-PROMO',
                'M1-LIMITED-PROMO',
                'M1-FINALE',
                'M1-ROTAM'
            ]);
        })->get();

        return count($items) > 0;
    }

    public function items() {
        return $this->hasMany(QuoteItem::class);
    }

    /**
     * Get payment info, should be replaced by Qoute property in the future
     *
     * @return string
     */
    public function getPaymentInfo()
    {
        $oneTimePayment = true;

        foreach($this->items as $item) {
            if($item->saleItemPrice !== null) {
                if ($item->saleItemPrice->name === 'Marketino ONE - Rottamazione') {
                    $oneTimePayment = false;
                }
            }
        }

        if ($oneTimePayment === true) {
            return 'Jednokratno';
        } else {
            return 'Obročno';
        }
    }

    public function containsMarketinoCashRegisterWithRates(): bool {

        $items = $this->items()->whereHas('saleItemPrice', function($q) {
            $q->where('short_id', 'LIKE', 'M1-RATE');
        })->get();

        return count($items) > 0;
    }

    public function getRateRows(): array {
        $item = $this->items()->with('saleItemPrice.saleItem')->whereHas('saleItemPrice', function($q) {
            $q->where('short_id', 'LIKE', 'M1-RATE');
        })->firstOrFail(); //only one for now

        //dd($item->saleItemPrice->_data);

        $rates = data_get($item->saleItemPrice->_data, 'rates', []);
        $vatKey = $item->saleItemPrice->saleItem->vat->key;

        $out = [];
        foreach($rates as $rate) {
            $totalNetAmount = $rate->unit_net_price * $item->quantity;
            $totalAmount = VatHelper::getAmountWithVat($totalNetAmount, SaleVat::fromKey($vatKey));
            $out[] = (object)[
                'name' => $rate->name,
                'quantity' => $item->quantity,
                'net_price' => $rate->unit_net_price,
                'total_net_amount' => $totalNetAmount,
                'total_amount' => $totalAmount
            ];
        }

        return $out;
    }
}
