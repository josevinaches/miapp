<?php

namespace App\Http\Controllers\Representante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Festero;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function festerosPdf(Request $request)
    {
        $ejercicio = (int) $request->input('ejercicio', now()->year);
        $comparsa = Auth::user()->comparsa;
        abort_if(!$comparsa, 403, 'No tienes comparsa asignada.');

        // Eager load tutores para menores
        $festeros = Festero::with([
            'tutores:id,festero_id,nombre,telefono,email',
            'cuotas' => fn($q) => $q->where('ejercicio', $ejercicio)
                ->select('id', 'festero_id', 'pagada_asociacion', 'pagada_fester')
        ])
            ->where('comparsa_id', $comparsa->id)
            ->orderBy('nombre')
            ->orderBy('primer_apellido')
            ->get();


        // Para marcar menores según tu regla (corte 24/07 del ejercicio)
        $cutoff = Carbon::createFromDate($ejercicio, 7, 24)->endOfDay();

        $pdf = Pdf::loadView('representante.exports.festeros_pdf', [
            'comparsa'  => $comparsa,
            'festeros'  => $festeros,
            'ejercicio' => $ejercicio,
            'cutoff'    => $cutoff,
        ])->setPaper('a4', 'portrait');

        $filename = 'festeros_' . $comparsa->id . '_' . $ejercicio . '.pdf';
        return $pdf->download($filename);
    }
}
