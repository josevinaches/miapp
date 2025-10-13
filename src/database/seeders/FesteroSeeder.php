<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comparsa;
use App\Models\Festero;

class FesteroSeeder extends Seeder
{
    public function run(): void
    {
        $perComparsa = 15; // ajusta cantidad

        Comparsa::query()->select('id')->chunk(100, function ($rows) use ($perComparsa) {
            foreach ($rows as $row) {
                Festero::factory()
                    ->count($perComparsa)
                    ->state(fn () => ['comparsa_id' => $row->id])
                    ->create();
            }
        });
    }
}
