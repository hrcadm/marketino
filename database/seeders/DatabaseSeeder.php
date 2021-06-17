<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(ActivityTypesSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(TicketTagSeeder::class);
        $this->call(SaleItemSeeder::class);
        $this->call(SaleItemSeeder2::class);    
        $this->call(SaleItemSeeder3::class);
    }
}
