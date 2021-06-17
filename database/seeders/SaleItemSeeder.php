<?php

namespace Database\Seeders;

use App\Enum\SaleItemType;
use App\Enum\SaleVat;
use App\Models\SaleItem;
use App\Models\SaleItemPrice;
use DB;
use Exception;
use Illuminate\Database\Seeder;
use Log;

class SaleItemSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $items = [
            [
                'name'   => 'Marketino ONE',
                'vat'    => SaleVat::VAT_22,
                'type'   => SaleItemType::PHYSICAL_PRODUCT,
                'prices' => [
                    [
                        'is_default'         => true,
                        'name'               => 'Marketino ONE - Promo',
                        'original_net_price' => 799,
                        'net_price'          => 699,
                        'short_id'           => 'M1-PROMO'
                    ],
                    [
                        'name'               => 'Marketino ONE - Promo',
                        'original_net_price' => 799,
                        'net_price'          => 699,
                        'discount_name'      => 'Sconto per la consegna di 40 giorni',
                        'discount_amount'    => 100,
                        'description'        => 'Promo prezzo con sconto per la consegna di 40 giorni',
                        'short_id'           => 'M1-FINALE'
                    ],
                    [
                        'name'               => 'Marketino ONE - Rottamazione',
                        'original_net_price' => 799,
                        'net_price'          => 499,
                        'description'        => 'Rottamazione',
                        'short_id'           => 'M1-ROTAM'
                    ]
                ]
            ],
            [
                'name'   => 'Lettore Carta',
                'vat'    => SaleVat::VAT_22,
                'type'   => SaleItemType::PHYSICAL_PRODUCT,
                'prices' => [
                    [
                        'is_default'         => true,
                        'name'               => 'Lettore Carta - Promo',
                        'original_net_price' => 79,
                        'net_price'          => 49,
                        'short_id'           => 'CR-PROMO',
                        '_data'              => '{"shipping_fee":"when_alone"}'
                    ],
                ]
            ],
            [
                'name'   => 'Inserimento',
                'vat'    => SaleVat::VAT_22,
                'type'   => SaleItemType::PHYSICAL_PRODUCT,
                'prices' => [
                    [
                        'is_default'         => true,
                        'name'               => 'Setup - Promo',
                        'original_net_price' => 50,
                        'net_price'          => 0,
                        'short_id'           => 'SETUP-PROMO',
                        '_data'              => '{"max":1}'
                    ],
                ]
            ],
            [
                'name'   => 'Spedizione',
                'vat'    => SaleVat::VAT_22,
                'type'   => SaleItemType::SERVICE,
                'prices' => [
                    [
                        'is_default' => true,
                        'name'       => 'Spedizione',
                        'net_price'  => 5,
                        'short_id'   => 'SHIPMENT',
                        '_data'      => '{"max":1}'
                    ],
                ]
            ]
        ];

        foreach($items as $item) {
            DB::beginTransaction();
            try {
                $prices = $item['prices'];
                unset($item['prices']);
                $saleItem = SaleItem::create($item);
                foreach($prices as $price) {
                    $price['sale_item_id'] = $saleItem->id;
                    SaleItemPrice::create($price);
                }
                DB::commit();
            } catch(Exception $e) {
                Log::error($e->getMessage());
                DB::rollback();
                throw $e;
            }
        }

    }
}
