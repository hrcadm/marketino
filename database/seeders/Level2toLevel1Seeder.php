<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Level2toLevel1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('support_tickets')->where('department', 'level_2')->update(['department' => 'level_1']);
    }
}
