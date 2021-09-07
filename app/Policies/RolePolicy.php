<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  User  $user
     * @param  string  $ability
     * @return Response|bool
     */
/*    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }*/

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        $abilities = $user->getPermissionsViaRoles()->pluck('name');

        foreach ($abilities as $ability) {
            if ($ability === 'view any role') {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Role $role
     * @return Response|bool
     */
    public function view(User $user, Role $role)
    {
        $abilities = $user->getPermissionsViaRoles()->pluck('name');

        foreach ($abilities as $ability) {
            if ($ability === 'view role') {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        $abilities = $user->getPermissionsViaRoles()->pluck('name');

        foreach ($abilities as $ability) {
            if ($ability === 'create role') {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Role $role
     * @return Response|bool
     */
    public function update(User $user, Role $role)
    {
        $abilities = $user->getPermissionsViaRoles()->pluck('name');

        foreach ($abilities as $ability) {
            if ($ability === 'update role') {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Role $role
     * @return Response|bool
     */
    public function delete(User $user, Role $role)
    {
        $abilities = $user->getPermissionsViaRoles()->pluck('name');

        foreach ($abilities as $ability) {
            if ($ability === 'delete role') {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Role $role
     * @return Response|bool
     */
    public function restore(User $user, Role $role)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Role $role
     * @return Response|bool
     */
    public function forceDelete(User $user, Role $role)
    {
        return false;
    }
}
