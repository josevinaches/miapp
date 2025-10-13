<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use App\Models\Festero;

class AdminComparsaController extends Controller
{
    public function assignForm()
    {
        // Representantes (muestra todos o solo libres; aquí todos ordenados)
        $representantes = \App\Models\User::role('Representante')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
        // Comparsas (con representante asignado o no, ordenadas por nombre)
        $comparsas = \App\Models\Comparsa::with([
            'bando:id,tipo,nombre',
            'representante:id,name,email',
        ])
            ->withCount('festeros')      // 👈 contador
            ->select('id', 'nombre', 'bando_id', 'user_id')
            ->orderBy('nombre')
            ->get();
        // Comparsas (para <select> en el formulario)
        $comparsasSelect = \App\Models\Comparsa::select('id', 'nombre')
            ->withCount('festeros')      // 👈 opcional: también en el <select>
            ->orderBy('nombre')
            ->get();


        // Bandos (para filtro)
        $bandos = \App\Models\Bando::select('id', 'tipo', 'nombre')
            ->orderBy('tipo')
            ->get();

        return view('admin.comparsas.assign', compact(
            'representantes',
            'comparsas',
            'comparsasSelect',
            'bandos'
        ));

        $ejercicio = (int) now()->year;

        $comparsas = \App\Models\Comparsa::select('id', 'nombre', 'bando_id', 'user_id')
            ->with([
                'bando:id,tipo,nombre',
                'representante:id,name,email',
                'festeros' => function ($q) use ($ejercicio) {
                    $q->select('id', 'comparsa_id'); // lean
                    $q->with(['cuotas' => function ($c) use ($ejercicio) {
                        $c->where('ejercicio', $ejercicio)->select('id', 'festero_id', 'ejercicio', 'cuota_asociacion', 'cuota_fester', 'pagada_asociacion', 'pagada_fester');
                    }]);
                }
            ])
            ->withCount('festeros')
            ->orderBy('nombre')
            ->get();

        $comparsasSelect = \App\Models\Comparsa::select('id', 'nombre')->withCount('festeros')->orderBy('nombre')->get();
        $bandos = \App\Models\Bando::select('id', 'tipo', 'nombre')->orderBy('tipo')->get();

        return view('admin.comparsas.assign', compact('representantes', 'comparsas', 'comparsasSelect', 'bandos', 'ejercicio'));
    }

    public function assignStore(Request $request)
    {
        $request->validate([
            'comparsa_id' => ['required', 'integer', 'min:1'],
            'user_id'     => ['required', 'integer', 'min:1'],
        ]);

        if (!Schema::hasColumn('comparsas', 'user_id')) {
            throw ValidationException::withMessages([
                'user_id' => 'La tabla comparsas no tiene la columna user_id. No se ha hecho ningún cambio.',
            ]);
        }

        $Comparsa = app('App\\Models\\Comparsa');
        $User     = app('App\\Models\\User');

        $comparsa = $Comparsa::findOrFail((int)$request->input('comparsa_id'));

        // Debe ser Representante
        $user = method_exists($User, 'role')
            ? $User::role('Representante')->findOrFail((int)$request->input('user_id'))
            : $User::findOrFail((int)$request->input('user_id'));

        // Regla 1 a 1: si ya está asignado a otra comparsa, error
        $yaAsignada = $Comparsa::where('user_id', $user->id)
            ->where('id', '<>', $comparsa->id)
            ->first();

        if ($yaAsignada) {
            throw ValidationException::withMessages([
                'user_id' => "El representante ya está asignado a la comparsa: {$yaAsignada->nombre}.",
            ]);
        }

        DB::transaction(function () use ($comparsa, $user) {
            $comparsa->forceFill(['user_id' => $user->id])->save();
        });

        return back()->with('status', 'Representante asignado correctamente.');
    }

    public function unassign(int $comparsaId, Request $request)
    {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('comparsas', 'user_id')) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'comparsa_id' => 'La tabla comparsas no tiene la columna user_id.',
            ]);
        }

        $Comparsa = app('App\\Models\\Comparsa');
        $comparsa = $Comparsa::findOrFail($comparsaId);

        // Si ya está libre, no hacemos nada
        if (is_null($comparsa->user_id)) {
            return back()->with('status', 'La comparsa ya estaba sin representante.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($comparsa) {
            $comparsa->forceFill(['user_id' => null])->save();
        });

        return back()->with('status', 'Representante desasignado correctamente.');
    }

    public function festeros(Request $request, \App\Models\Comparsa $comparsa)
    {
        $q = trim((string) $request->input('q', ''));
       $ejercicio = (int) $request->input('ejercicio', now()->year); // mejor que date('Y')

        $festeros = Festero::where('comparsa_id', $comparsa->id)
            ->when($q !== '', function ($query) use ($q) {
                $like = "%{$q}%";
                $query->where(function ($sub) use ($like) {
                    $sub->where('nombre', 'like', $like)
                        ->orWhere('dni', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('telefono', 'like', $like);
                });
            })
            ->withCount('tutores')   // 👶 contador de tutores por si lo usas en la tabla
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('admin.comparsas.festeros', compact('comparsa', 'festeros', 'q', 'ejercicio'));
    }


    public function menores(\Illuminate\Http\Request $request, \App\Models\Comparsa $comparsa)
    {
        $ejercicio = (int)($request->integer('ejercicio') ?: now()->year);
        $corte = \Carbon\Carbon::create($ejercicio, 7, 24);

        $festeros = $comparsa->festeros()
            ->with(['tutores:id,festero_id,nombre,dni,parentesco,telefono,email'])
            ->get(['id', 'comparsa_id', 'nombre', 'dni', 'fecha_nacimiento', 'email', 'telefono']);

        $menores = $festeros->filter(function ($f) use ($corte) {
            if (empty($f->fecha_nacimiento)) return false;
            $nac = \Carbon\Carbon::parse($f->fecha_nacimiento);
            return $nac->diffInYears($corte) < 18;
        });

        return view('admin.comparsas.menores', [
            'comparsa' => $comparsa,
            'ejercicio' => $ejercicio,
            'menores' => $menores,
        ]);
    }
}
