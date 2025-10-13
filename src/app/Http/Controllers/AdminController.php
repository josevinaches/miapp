<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{


    public function index(Request $request)
    {
        $ejercicio = (int) $request->input('ejercicio', now()->year);

        $stats = DB::table('comparsas as c')
            ->join('users as u', 'u.id', '=', 'c.user_id')
            ->leftJoin('festeros as f', 'f.comparsa_id', '=', 'c.id')
            ->leftJoin('cuotas as q', function ($j) use ($ejercicio) {
                $j->on('q.festero_id', '=', 'f.id')
                    ->where('q.ejercicio', '=', $ejercicio);
            })
            ->groupBy('u.id', 'u.name', 'c.id', 'c.nombre')
            ->selectRaw("
            u.id   as representante_id,
            u.name as representante,
            c.id   as comparsa_id,
            c.nombre as comparsa,
            COUNT(DISTINCT f.id) as total_festeros,
            ROUND(
                SUM(
                    COALESCE(CASE WHEN q.pagada_asociacion = 0 THEN q.cuota_asociacion ELSE 0 END, 0)
                  + COALESCE(CASE WHEN q.pagada_fester      = 0 THEN q.cuota_fester      ELSE 0 END, 0)
                ), 2
            ) as adeudado
        ")
            ->orderBy('representante')
            ->get();

        $totales = [
            'festeros' => $stats->sum('total_festeros'),
            'adeudado' => number_format($stats->sum('adeudado'), 2, ',', '.'),
        ];

        return view('admin.dashboard', compact('stats', 'totales', 'ejercicio'));
    }
}
