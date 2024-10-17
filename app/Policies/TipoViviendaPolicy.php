<?php

namespace App\Policies;

use App\Models\TipoVivienda;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TipoViviendaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canIndex('TIPO_VIVIENDA');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TipoVivienda $tipoVivienda): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canCrear('TIPO_VIVIENDA');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TipoVivienda $tipoVivienda): bool
    {
        return $user->canEditar('TIPO_VIVIENDA');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TipoVivienda $tipoVivienda): bool
    {
        return $user->canEliminar('TIPO_VIVIENDA');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TipoVivienda $tipoVivienda): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TipoVivienda $tipoVivienda): bool
    {
        return $user->isSuperAdmin();
    }
}
