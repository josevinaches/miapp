protected $middlewareAliases = [
'auth' => \App\Http\Middleware\Authenticate::class,
'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
// ...
'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
// ⬇️ nuestro alias
'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
];
