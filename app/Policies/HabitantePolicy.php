<?php

namespace App\Policies;

use App\Models\Habitante;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HabitantePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canIndex('HABITANTE');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Habitante $habitante): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canCrear('HABITANTE');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Habitante $habitante): bool
    {
        return $user->canEditar('HABITANTE');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Habitante $habitante): bool
    {
        return $user->canEliminar('HABITANTE');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Habitante $habitante): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Habitante $habitante): bool
    {
        return $user->isSuperAdmin();
    }
}
