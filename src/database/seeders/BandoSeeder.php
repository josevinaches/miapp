<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bando;
use Illuminate\Support\Str;

class BandoSeeder extends Seeder
{
    /** * Run the database seeds. */ public function run(): void
    {
        $moros = [
            'Moros del Riff',
            'Moros de Touareg',
            'Pirates Berberiscos',
            'Artillería del Islam',
            'Moros Mercaders',
            'Moros Beduins',
            'Moros de Capeta',
            'Moros Pakkos',
            'Artillería Mora',
            'Guardia Negra',
            'Negres',
        ];

        $cristianos = [
            'Pirates Corsaris',
            'Contrabandistes',
            'Pescadors',
            'Artillería Cristiana',
            'Caçadors',
            'Catalans',
            'Llauradors',
            'Marinos',
            'Destralers',
            'Voluntaris',
            'Almogàvers',
        ];

        foreach ($moros as $nombre) {
            Bando::updateOrCreate(
                ['nombre' => $nombre],
                [
                    'slug' => Str::slug($nombre, '-'),
                    'tipo' => 'Moro',
                ]
            );
        }
        foreach ($cristianos as $nombre) {
            Bando::updateOrCreate(
                ['nombre' => $nombre],
                [

                'slug' => Str::slug($nombre, '-'),
                'tipo' => 'Cristiano',

                ]

            );
        }
    }
}
