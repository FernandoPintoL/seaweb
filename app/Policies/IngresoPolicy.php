<?php

namespace App\Policies;

use App\Models\Ingreso;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IngresoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canIndex('INGRESO');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ingreso $ingreso): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canCrear(modelo: 'INGRESO');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ingreso $ingreso): bool
    {
        return $user->canEditar('INGRESO');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ingreso $ingreso): bool
    {
        return $user->canEliminar('INGRESO');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ingreso $ingreso): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ingreso $ingreso): bool
    {
        return $user->isSuperAdmin();
    }
}
