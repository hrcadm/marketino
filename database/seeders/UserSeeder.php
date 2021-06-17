<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @noinspection SpellCheckingInspection
     */
    public function run()
    {
        $users = [
            [
                'name'     => 'Vanja Retkovac',
                'email'    => 'vanja.retkovac@superius.hr',
                'password' => \Hash::make('KRmbAtPHRKNJUETVSPzr9dsDda35'),
            ],
            [
                'name'     => 'Drazen Cvjetkovic',
                'email'    => 'drazen.cvjetkovic@superius.hr',
                'password' => \Hash::make('1234'),
            ],
            [
                'name'     => 'Erik Roznbeker',
                'email'    => 'erik.roznbeker@superius.hr',
                'password' => \Hash::make('1234'),
            ],
            [
                'name'     => 'Ana Gomezel',
                'email'    => 'ana@marketino.it',
                'password' => \Hash::make('1234'),
            ],
            [
                'name'     => 'Tamara Besic',
                'email'    => 'tamara@marketino.it',
                'password' => \Hash::make('1234'),
            ],

            [
                'name'     => 'Silvia Lorencin',
                'email'    => 'silvia@marketino.it',
                'password' => \Hash::make('1234'),
            ],
            [
                'name'     => 'Moreno Brusic',
                'email'    => 'moreno@marketino.it',
                'password' => \Hash::make('1234'),
            ],

            [
                'name'     => 'Ira Rubinic',
                'email'    => 'ira.rubinic@superius.hr',
                'password' => \Hash::make('1234'),
            ],

            [
                'name'     => 'Andrea Pagura',
                'email'    => 'andrea.pagura@superius.hr',
                'password' => \Hash::make('1234'),
            ],

            [
                'name'     => 'Zoran Lukic',
                'email'    => 'lukiczoran96@gmail.com',
                'password' => \Hash::make('1234'),
            ],

        ];

        User::factory()
            ->count(count($users))
            ->state(new Sequence(...$users))
            ->create();
    }
}
