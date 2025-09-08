<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
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

        // precisa retornar um verdade para ir para a próxima etapa
        Gate::define('admin', function (User $user){
            //dd($user);
            return $user->role === 'admin';
        });

        //Gate::define('user', function ($user){
        //    return $user->role === 'user';
        //});
    }
}
