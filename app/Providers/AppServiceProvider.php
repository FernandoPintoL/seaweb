<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


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
        // Schema::defaultStringLength(191);
        if(config('app.env') === 'production'){
            URL::forceScheme( 'https' );
        }

        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        Inertia::share([
            'auth.roles' => function () {
                if (Auth::check()) {
                    $auth_user = Auth::user();
                    $user      = User::find( $auth_user->id );
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'roles' => $user->getRoleNames()->pluck("name"), // Obtener roles
                        'permissions' => $user->getPermissionNames()->pluck('name'), // Obtener permisos
                    ];
                }
                return null;
            },
        ]);

    }
}