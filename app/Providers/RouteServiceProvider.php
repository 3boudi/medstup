<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     */
   public function boot(): void
{
    $this->routes(function () {
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php')); // ضروري هذا السطر

        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    });
}
protected function redirectTo(): string
{
    return '/api/user/login'; // أو أي Route حاب تعيده له
}

}
