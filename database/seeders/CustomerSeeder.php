<?php

namespace Database\Seeders;

use App\Models\ActivityType;
use App\Models\Customer;
use App\Models\CustomerEmail;
use App\Models\CustomerPhone;
use DB;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        Customer::factory(15)->create()->each(function($customer) {
            CustomerEmail::factory()->create([
                'customer_id' => $customer->id,
                'is_default'     => true
            ]);
            CustomerPhone::factory()->create([
                'customer_id' => $customer->id,
                'is_default'     => true
            ]);
        });
    }
}
