<?php

namespace Database\Seeders;

use App\Models\SaleItem;
use Illuminate\Database\Seeder;

class SaleItemSeeder2 extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $saleItemM1 = SaleItem::where('name', '=', 'Marketino ONE')->firstOrFail();

        $saleItemM1->prices()->create([
            'name'      => 'RATA 1 - Marketino ONE',
            'net_price' => 229,
            'short_id'  => 'M1-RATE',
            '_data'     => [
                "rates" => [
                    [
                        "name"           => "RATA 2 - Marketino ONE",
                        "unit_net_price" => 229
                    ],
                    [
                        "name"           => "RATA 3 - Marketino ONE",
                        "unit_net_price" => 229
                    ],
                    [
                        "name"           => "RATA 4 - Marketino ONE",
                        "unit_net_price" => 229
                    ]
                ]
            ]
        ]);
    }
}
