<?php

namespace App\Http\Controllers\Representante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Festero;
use App\Models\Cuota;

class CuotaController extends Controller
{



    public function applyMass(Request $request)
    {
        $data = $request->validate([
            'ejercicio'        => ['required', 'integer', 'min:2000', 'max:2100'],
            'cuota_asociacion' => ['required', 'numeric', 'min:0'],
            'cuota_fester'     => ['required', 'numeric', 'min:0'],
        ]);

        $comparsa = Auth::user()->comparsa;
        abort_if(!$comparsa, 403, 'No tienes comparsa asignada.');

        $aplicadas = 0;
        $omitidas  = 0;

        // Menor si su 18º cumpleaños es posterior al 24/07 del ejercicio
        $cutoff = Carbon::createFromDate($data['ejercicio'], 7, 24)->endOfDay();

        Festero::where('comparsa_id', $comparsa->id)
            ->select(['id', 'fecha_nacimiento'])
            ->orderBy('id')
            ->chunk(200, function ($festeros) use ($data, $cutoff, &$aplicadas, &$omitidas) {
                foreach ($festeros as $f) {
                    $esMenor = $f->fecha_nacimiento
                        ? Carbon::parse($f->fecha_nacimiento)->gt($cutoff->copy()->subYears(18))
                        : false;

                    if ($esMenor) {
                        $omitidas++;
                        continue;
                    }

                    Cuota::updateOrCreate(
                        ['festero_id' => $f->id, 'ejercicio' => $data['ejercicio']],
                        [
                            'cuota_asociacion' => $data['cuota_asociacion'],
                            'cuota_fester'     => $data['cuota_fester'],
                        ]
                    );
                    $aplicadas++;
                }
            });

        return back()->with('ok', "Cuotas aplicadas: {$aplicadas}. Omitidas por menor: {$omitidas}.");
    }

    // Verifica que el festero pertenece a la comparsa del representante logado
    private function festeroAutorizado(Request $request, Festero $festero): Festero
    {
        $comparsa = $request->user()->comparsa; // via relación Comparsa::where('user_id', auth()->id())
        abort_if(!$comparsa || $festero->comparsa_id !== $comparsa->id, 403);
        return $festero;
    }

    public function edit(Request $request, Festero $festero)
    {
        $this->festeroAutorizado($request, $festero);

        $ejercicio = now()->year;
        $cuota = $festero->cuotas()->firstOrCreate(
            ['ejercicio' => $ejercicio],
            ['cuota_asociacion' => 0, 'cuota_fester' => 0]
        );

        return view('representante/cuotas/edit', compact('festero', 'cuota', 'ejercicio'));
    }

    public function update(Request $request, Festero $festero)
    {
        $this->festeroAutorizado($request, $festero);

        $data = $request->validate([
            'ejercicio' => ['required', 'integer', 'min:2000', 'max:2100'],
            'cuota_asociacion' => ['required', 'numeric', 'min:0'],
            'cuota_fester'     => ['required', 'numeric', 'min:0'],
            'pagada_asociacion' => ['nullable', 'boolean'],
            'pagada_fester'    => ['nullable', 'boolean'],
        ]);

        $cuota = $festero->cuotas()->firstOrCreate(['ejercicio' => $data['ejercicio']]);
        $cuota->cuota_asociacion = $data['cuota_asociacion'];
        $cuota->cuota_fester     = $data['cuota_fester'];

        $cuota->pagada_asociacion = (bool)($data['pagada_asociacion'] ?? false);
        $cuota->pagada_fester     = (bool)($data['pagada_fester'] ?? false);

        if ($cuota->pagada_asociacion && is_null($cuota->pagada_asociacion_at)) {
            $cuota->pagada_asociacion_at = now();
        }
        if ($cuota->pagada_fester && is_null($cuota->pagada_fester_at)) {
            $cuota->pagada_fester_at = now();
        }

        $cuota->save();

        return redirect()->route('representante.festeros.index')->with('status', 'Cuota guardada.');
    }
}
