<?php

namespace App\Policies;

use App\Models\TipoVisita;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TipoVisitaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canIndex('TIPO_VISITA');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TipoVisita $tipoVisita): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canCrear('TIPO_VISITA');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TipoVisita $tipoVisita): bool
    {
        return $user->canEditar('TIPO_VISITA');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TipoVisita $tipoVisita): bool
    {
        return $user->canEliminar('TIPO_VISITA');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TipoVisita $tipoVisita): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TipoVisita $tipoVisita): bool
    {
        return $user->isSuperAdmin();
    }
}
