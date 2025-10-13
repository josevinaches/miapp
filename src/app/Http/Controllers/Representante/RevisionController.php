<?php

namespace App\Http\Controllers\Representante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Festero;
use App\Models\Revision;
use Carbon\Carbon;

class RevisionController extends Controller
{
    public function enviar(Request $request)
    {
        $ejercicio = (int) $request->input('ejercicio', now()->year);
        $comparsa = Auth::user()->comparsa;
        abort_if(!$comparsa, 403);

        // Cargar datos (mismo criterio que PDF de festeros)
        $festeros = Festero::with([
                'tutores:id,festero_id,nombre,telefono,email',
                'cuotas' => fn($q) => $q->where('ejercicio', $ejercicio)
                    ->select('id','festero_id','pagada_asociacion','pagada_fester')
            ])
            ->where('comparsa_id', $comparsa->id)
            ->orderBy('nombre')->orderBy('primer_apellido')
            ->get();

        $cutoff = Carbon::createFromDate($ejercicio, 7, 24)->endOfDay();

        // Render PDF (reutiliza la vista que ya hicimos)
        $pdf = Pdf::loadView('representante.exports.festeros_pdf', [
            'comparsa'  => $comparsa,
            'festeros'  => $festeros,
            'ejercicio' => $ejercicio,
            'cutoff'    => $cutoff,
        ])->setPaper('a4','portrait');

        // Guardar a storage
        $filename = "revisiones/comp{$comparsa->id}_{$ejercicio}_".time().".pdf";
        Storage::disk('public')->put($filename, $pdf->output());

        // Crear registro de revisión
        Revision::create([
            'comparsa_id'      => $comparsa->id,
            'representante_id' => Auth::id(),
            'ejercicio'        => $ejercicio,
            'archivo_pdf'      => $filename,
            'estado'           => 'pendiente',
        ]);

        return back()->with('ok', 'Enviado al Admin para revisión.');
    }
}
