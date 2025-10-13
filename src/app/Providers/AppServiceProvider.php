<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Uso en Blade:
        // class="@active('admin.*','representante.*','expedientes.*')"
        Blade::directive('active', function ($patterns) {
            return "<?php echo request()->routeIs(...[$patterns]) ? 'text-indigo-600 font-semibold' : ''; ?>";
        });
    }
}
