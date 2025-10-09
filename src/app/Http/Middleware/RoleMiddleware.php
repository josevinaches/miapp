<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware {
    public function handle(Request $request, Closure $next, ...$roles) {
        $user = $request->user();
        if (!$user) { abort(401); }
        if (count($roles) === 1 && str_contains($roles[0], "|")) { $roles = explode("|", $roles[0]); }
        if (!method_exists($user, "hasAnyRole")) { abort(500, "HasRoles trait missing"); }
        if (!$user->hasAnyRole($roles)) { abort(403); }
        return $next($request);
    }
}
