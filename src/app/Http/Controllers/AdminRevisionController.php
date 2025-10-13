<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Revision;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminRevisionController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->input('estado'); // opcional
        $ejercicio = (int) $request->input('ejercicio', now()->year);

        $revisiones = Revision::with(['comparsa:id,nombre','representante:id,name','admin:id,name'])
            ->when($estado, fn($q)=>$q->where('estado',$estado))
            ->where('ejercicio',$ejercicio)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.revisiones.index', compact('revisiones','estado','ejercicio'));
    }

    public function actualizar(Request $request, Revision $revision)
    {
        $data = $request->validate([
            'estado' => ['required','in:revisado,aprobado,rechazado'],
            'admin_comentario' => ['nullable','string','max:2000'],
        ]);

        $revision->update([
            'estado' => $data['estado'],
            'admin_comentario' => $data['admin_comentario'] ?? null,
            'admin_id' => $request->user()->id,
            'revisado_at' => now(),
        ]);

        return back()->with('ok','Revisión actualizada.');
    }
}
