<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comparsa;
use Illuminate\Http\Request;

class AdminComparsaController extends Controller
{
    public function assignForm()
    {
        $representantes = User::where('role','representante')->orderBy('name')->get();
        $comparsas = Comparsa::whereNull('user_id')
            ->with('bando:id,nombre')
            ->orderBy('bando_id')->orderBy('nombre')
            ->get()
            ->groupBy('bando.nombre'); // para agrupar por bando en el select
        return view('admin.assign-comparsa', compact('representantes','comparsas'));
    }

    public function assignStore(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'comparsa_id' => 'required|exists:comparsas,id',
        ]);

        $comparsa = Comparsa::findOrFail($data['comparsa_id']);

        if ($comparsa->user_id) {
            return back()->with('error','Esa comparsa ya tiene representante.');
        }
        if (Comparsa::where('user_id', $data['user_id'])->exists()) {
            return back()->with('error','Ese representante ya tiene una comparsa.');
        }

        $comparsa->update(['user_id' => $data['user_id']]);
        return back()->with('ok','Comparsa asignada ✅');
    }
}

