<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
class AppServiceProvider extends ServiceProvider
{
    /**
     * The namespace for the controller routes.
     *
     * @var string|null
     */
protected $namespace = 'App\Http\Controllers';

/**
 * Register any application services.
 */
public function register(): void
{
    //
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }

}