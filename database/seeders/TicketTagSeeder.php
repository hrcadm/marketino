<?php

namespace Database\Seeders;

use App\Enum;
use App\Models;
use Illuminate\Database\Seeder;

class TicketTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @noinspection SpellCheckingInspection
     */
    public function run()
    {
        $ticket_tags = collect(
            [
                ['tag' => 'Problem sa isporukom', 'type' => Enum\TicketTagType::NEW],
                ['tag' => 'Povrat sredstava', 'type' => Enum\TicketTagType::NEW],
                ['tag' => 'Kvar uređaja (zamjenski uređaj)', 'type' => Enum\TicketTagType::NEW],
                ['tag' => 'Problem s administracijom', 'type' => Enum\TicketTagType::NEW],
                ['tag' => 'Neosalon aplikacija', 'type' => Enum\TicketTagType::NEW],
                ['tag' => 'Mjesečno održavanje', 'type' => Enum\TicketTagType::NEW],

                ['tag' => 'As is', 'type' => Enum\TicketTagType::RESOLVED],
                ['tag' => 'Bug', 'type' => Enum\TicketTagType::RESOLVED],
                ['tag' => 'Nekategorizirano', 'type' => Enum\TicketTagType::RESOLVED],
                ['tag' => 'Upozoriti L1 da je sam trebao riješiti', 'type' => Enum\TicketTagType::RESOLVED],
            ]
        );

        $ticket_tags->each(
            static function ($attributes) {
                Models\TicketTag::create($attributes);
            }
        );
    }
}
