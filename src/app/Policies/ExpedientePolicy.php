<?php

namespace App\Policies;

use App\Models\{Expediente, User};

class ExpedientePolicy
{
    // Admin: acceso total
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('Admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        // Admin o Representante pueden listar
        return $user->hasRole('Admin') || $user->hasRole('Representante');
    }

    public function view(User $user, Expediente $e): bool
    {
        // Admin ve todo; propietario ve lo suyo; todos ven los creados por Admin
        if ($e->user_id === $user->id) return true;

        $owner = $e->relationLoaded('user') ? $e->user : $e->user()->first();
        return $owner?->hasRole('Admin') ?? false;
    }

    public function create(User $user): bool
    {
        // Ambos pueden crear (ajusta si no quieres que el Rep cree)
        return $user->hasRole('Admin') || $user->hasRole('Representante');
    }

    public function update(User $user, Expediente $e): bool
    {
        // Solo Admin o propietario
        return $e->user_id === $user->id;
    }

    public function delete(User $user, Expediente $e): bool
    {
        // Solo Admin o propietario
        return $e->user_id === $user->id;
    }

    public function restore(User $user, Expediente $e): bool { return false; }
    public function forceDelete(User $user, Expediente $e): bool { return false; }

    
}
