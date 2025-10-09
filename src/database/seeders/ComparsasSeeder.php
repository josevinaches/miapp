<?php

namespace Database\Seeders;

use App\Models\Bando;
use App\Models\Comparsa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ComparsasSeeder extends Seeder
{
    public function run(): void
    {
        // Crea bandos base
        $moro = Bando::firstOrCreate(['slug' => 'moro'], ['nombre' => 'Bando Moro']);
        $cris = Bando::firstOrCreate(['slug' => 'cristiano'], ['nombre' => 'Bando Cristiano']);

        // Lee el fichero directamente del sistema de archivos
        $path = base_path('storage/app/BandoMoroYCristiano.txt');
        if (!file_exists($path)) {
            $this->command->error("No existe el fichero: $path");
            return;
        }

        $raw = file_get_contents($path);
        $lines = preg_split('/\R/', $raw);

        $current = null; // 'moro' | 'cristiano'
        $creadas = ['moro' => 0, 'cristiano' => 0];

        foreach ($lines as $lineRaw) {
            $line = trim($lineRaw);
            if ($line === '') continue;

            // Cabeceras
            $lower = Str::lower($line);
            if (Str::startsWith($lower, 'bando moro')) { $current = 'moro'; continue; }
            if (Str::startsWith($lower, 'bando cristiano')) { $current = 'cristiano'; continue; }

            // Quita bullets/numeración y separadores
            $line = preg_replace('/^\s*([-*•]|\d+[.)-])\s*/u', '', $line);
            if ($line === '' || Str::endsWith($line, ':')) continue;

            $bando = $current === 'moro' ? $moro : ($current === 'cristiano' ? $cris : null);
            if (!$bando) continue;

            $created = Comparsa::firstOrCreate(
                ['nombre' => $line],
                ['bando_id' => $bando->id, 'user_id' => null]
            );
            if ($created->wasRecentlyCreated) {
                $creadas[$current]++;
            }
        }

        $this->command->info("Comparsas creadas → Moro: {$creadas['moro']} · Cristiano: {$creadas['cristiano']} ✅");
    }
}
