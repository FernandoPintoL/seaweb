<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Condominio;
use App\Models\Habitante;
use App\Models\GaleriaVehiculo;
use App\Models\GaleriaVisitante;
use App\Models\Ingreso;
use App\Models\Permissions;
use App\Models\Roles;
use App\Models\TipoDocumento;
use App\Models\TipoVisita;
use App\Models\TipoVivienda;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\Visitante;
use App\Models\Vivienda;
use App\Policies\CondominioPolicy;
use App\Policies\GaleriaVehiculoPolicy;
use App\Policies\GaleriaVisitantePolicy;
use App\Policies\HabitantePolicy;
use App\Policies\IngresoPolicy;
use App\Policies\PermissionsPolicy;
use App\Policies\RolesPolicy;
use App\Policies\TipoDocumentoPolicy;
use App\Policies\TipoVisitaPolicy;
use App\Policies\TipoViviendaPolicy;
use App\Policies\UserPolicy;
use App\Policies\VehiculoPolicy;
use App\Policies\VisitantePolicy;
use App\Policies\ViviendaPolicy;


class AppServiceProvider extends ServiceProvider
{
    protected $polices = [
        Condominio::class => CondominioPolicy::class,
        GaleriaVehiculo::class => GaleriaVehiculoPolicy::class,
        GaleriaVisitante::class => GaleriaVisitantePolicy::class,
        Habitante::class => HabitantePolicy::class,
        Ingreso::class => IngresoPolicy::class,
        Permission::class => PermissionsPolicy::class,
        Role::class => RolesPolicy::class,
        TipoDocumento::class => TipoDocumentoPolicy::class,
        TipoVisita::class => TipoVisitaPolicy::class,
        TipoVivienda::class => TipoViviendaPolicy::class,
        User::class => UserPolicy::class,
        Vehiculo::class => VehiculoPolicy::class,
        Visitante::class => VisitantePolicy::class,
        Vivienda::class => ViviendaPolicy::class,
    ];
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
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Gate::policy(Condominio::class, CondominioPolicy::class);
        Gate::policy(GaleriaVehiculo::class, GaleriaVehiculoPolicy::class);
        Gate::policy(GaleriaVisitante::class, GaleriaVisitantePolicy::class);
        Gate::policy(Habitante::class, HabitantePolicy::class);
        Gate::policy(Ingreso::class, IngresoPolicy::class);
        Gate::policy(Permission::class, PermissionsPolicy::class);
        Gate::policy(Role::class, RolesPolicy::class);
        Gate::policy(TipoDocumento::class, TipoDocumentoPolicy::class);
        Gate::policy(TipoVisita::class, TipoVisitaPolicy::class);
        Gate::policy(TipoVivienda::class, TipoViviendaPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Vehiculo::class, VehiculoPolicy::class);
        Gate::policy(Visitante::class, VisitantePolicy::class);
        Gate::policy(Vivienda::class, ViviendaPolicy::class);
        /*Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });*/

        Inertia::share([
            'auth.roles' => function () {
                if (Auth::check()) {
                    $auth_user = Auth::user();
                    $user      = User::find($auth_user->id);
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
