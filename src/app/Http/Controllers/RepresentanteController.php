<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RepresentanteController extends Controller
{
    public function index()
    {
        return view('representante.dashboard');  // Redirige a una vista de representante
    }
}
