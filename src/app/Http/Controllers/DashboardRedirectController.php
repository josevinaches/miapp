<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('Admin')) {
            return to_route('admin.dashboard');
        }

        if ($user->hasRole('Representante')) {
            return to_route('representante.dashboard');
        }

        abort(403, 'Tu usuario no tiene un rol asignado.');
    }
}
