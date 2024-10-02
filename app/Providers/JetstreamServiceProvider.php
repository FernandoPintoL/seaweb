<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class JetstreamServiceProvider extends ServiceProvider
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
        /*Gate::before(function ($user, $ability) {
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
                        'roles' => $user->getRoleNames(), // Obtener roles
                        'permissions' => $user->getAllPermissions()->pluck('name'), // Obtener permisos
                    ];
                }
                return null;
            },
        ]);*/

        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}