<?php

namespace App\Http\Controllers;

use App\Models\Festero;
use App\Models\Tutor;
use Illuminate\Http\Request;

class AdminTutorController extends Controller
{
    public function create(Festero $festero)
    {
        // muestra formulario para añadir tutor a este festero
        return view('admin.tutores.create', compact('festero'));
    }

    public function store(Request $request, Festero $festero)
    {
        $data = $request->validate([
            'nombre'     => ['required','string','max:255'],
            'dni'        => ['required','string','max:15'],
            'parentesco' => ['required','string','max:50'],
            'telefono'   => ['nullable','string','max:50'],
            'email'      => ['nullable','email','max:255'],
        ]);

        // normaliza DNI
        $data['dni'] = strtoupper(preg_replace('/\s|-/', '', $data['dni']));
        $data['festero_id'] = $festero->id;

        Tutor::create($data);

        // vuelve a la pantalla de menores de la comparsa
        return redirect()
            ->route('admin.comparsas.menores', $festero->comparsa_id)
            ->with('status', 'Tutor creado correctamente.');
    }

    public function destroy(Tutor $tutor)
    {
        $comparsaId = $tutor->festero->comparsa_id;
        $tutor->delete();

        return redirect()
            ->route('admin.comparsas.menores', $comparsaId)
            ->with('status', 'Tutor eliminado.');
    }
}
