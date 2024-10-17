<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Auth\Access\Response;

class VehiculoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canIndex('VEHICULO');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vehiculo $vehiculo): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canCrear('VEHICULO');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vehiculo $vehiculo): bool
    {
        return $user->canEditar('VEHICULO');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vehiculo $vehiculo): bool
    {
        return $user->canEliminar('VEHICULO');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vehiculo $vehiculo): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vehiculo $vehiculo): bool
    {
        return $user->isSuperAdmin();
    }
}
