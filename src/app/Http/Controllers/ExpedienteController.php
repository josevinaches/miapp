<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpedienteController extends Controller
{
    // app/Http/Controllers/ExpedienteController.php
    public function index(Request $request)
    {
        $this->authorize('viewAny', Expediente::class);

        $user = $request->user();

        $expedientes = Expediente::with('user')
            ->when(!$user->hasRole('Admin'), function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('user.roles', fn($r) => $r->where('name', 'Admin'));
            })
            ->latest()
            ->paginate(10);

        return view('expedientes.index', compact('expedientes'));
    }

    public function create()
    {
        $this->authorize('create', Expediente::class);
        return view('expedientes.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Expediente::class);

        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:abierto,en_progreso,cerrado',
        ]);

        $data['user_id'] = Auth::id();
        Expediente::create($data);

        return to_route('expedientes.index')->with('ok', 'Expediente creado ✅');
    }

    public function edit(Expediente $expediente)
    {
        $this->authorize('update', $expediente);
        return view('expedientes.edit', compact('expediente'));
    }

    public function update(Request $request, Expediente $expediente)
    {
        $this->authorize('update', $expediente);

        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:abierto,en_progreso,cerrado',
        ]);

        $expediente->update($data);
        return to_route('expedientes.index')->with('ok', 'Expediente actualizado ✏️');
    }

    public function destroy(Expediente $expediente)
    {
        $this->authorize('delete', $expediente);
        $expediente->delete();
        return back()->with('ok', 'Expediente eliminado 🗑️');
    }


    public function show(Request $request, Expediente $expediente)
    {
        $this->authorize('view', $expediente);
        return view('expedientes.show', compact('expediente'));
    }
}
