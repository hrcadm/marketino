<?php

namespace Database\Seeders;

use App\Models\ActivityType;
use Illuminate\Database\Seeder;

class ActivityTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @noinspection SpellCheckingInspection
     */
    public function run()
    {
        $activityTypes = [
            'Parrucchieri ',
            'Barbieri ',
            'Estetisti',
            'Truccatrici ',
            'Saloni di bellezza',
            'Massaggi',
            'Chirurghi Estetici ',
            'Servizio guasti',
            'Elettricisci',
            'Idraulici',
            'Supporto Tecnico',
            'Servizio di elettrodomestici',
            'Spazzacamino',
            'Pittori',
            'Risturazioni',
            'Balneari',
            'Appartamenti',
            'Autonoleggio (boat, scooter, bici)',
            'Stazioni Sciistiche',
            'Parco giochi',
            'Beach Bar',
            'Campeggi',
            'Sarti',
            'Calzolai',
            'Salone per Animali',
            'Tattoo Studio',
            'Degustazioni',
            'Funeria e Cimiteriali',
            'Avvocati',
            'Artigiano',
            'Commerciante',
            'Bigioteria artigianale',
            'Autoscuole',
            'Profumerie',
            'Art Shop',
            'Tabacco Shop',
            'Candy shop',
            'Abbigliamento',
            'Antiquariato',
            'Fioraio',
            'Fabbricanti',
            'Venditori di abbigliamento',
            'Food truck',
            'Panificio',
            'Pasticceria',
            'Pop corn',
            'Venditori di gelato',
            'Dolciumi',
            'Caldarroste',
            'olive',
            'Sementi',
            'B&B, appartamenti, ecc',
            'Escursioni (barche, trenini)',
        ];

        foreach ($activityTypes as $type){
            $activityType = new ActivityType();
            $activityType->name = $type;
            $activityType->save();
        }

    }

}
