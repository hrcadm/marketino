<?php

namespace Database\Seeders;

use App\Models\SaleItem;
use Illuminate\Database\Seeder;

class SaleItemSeeder3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $saleItemM1 = SaleItem::where('name', '=', 'Marketino ONE')->firstOrFail();

        $saleItemM1->prices()->create([
			'is_default'         => true,
			'name'               => 'Marketino ONE - Limited promo',
			'original_net_price' => 799,
			'net_price'          => 499,
			'short_id'           => 'M1-LIMITED-PROMO'
        ]);
    }
}
