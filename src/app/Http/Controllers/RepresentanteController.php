<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RepresentanteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load(['comparsa.bando:id,tipo,nombre']);
        return view('representante.dashboard', [
            'user'     => $user,
            'comparsa' => $user->comparsa,     // puede ser null
        ]);
    }
}
