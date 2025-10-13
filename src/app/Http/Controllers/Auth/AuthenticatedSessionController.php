<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // Usa el flujo estándar (incluye rate limit del LoginRequest/Fortify)
            $request->authenticate();
        } catch (ValidationException $e) {
            // Credenciales inválidas -> redirige a welcome con flash
            return redirect()
                ->route('welcome')
                ->with('login_error', 'Credenciales inválidas.');
        }

        $request->session()->regenerate();

        // Centraliza desvío por rol en /dashboard
        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}
