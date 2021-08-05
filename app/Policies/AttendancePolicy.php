<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AttendancePolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability): bool
    {
        if ($user->isAdmin()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('view any attendance');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Attendance $attendance
     * @return Response|bool
     */
    public function view(User $user, Attendance $attendance)
    {
        return $user->can('view attendance');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create attendance');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Attendance $attendance
     * @return Response|bool
     */
    public function update(User $user, Attendance $attendance)
    {
        return $user->can('update attendance');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Attendance $attendance
     * @return Response|bool
     */
    public function delete(User $user, Attendance $attendance)
    {
        return $user->can('delete attendance');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Attendance $attendance
     * @return Response|bool
     */
    public function restore(User $user, Attendance $attendance)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Attendance $attendance
     * @return Response|bool
     */
    public function forceDelete(User $user, Attendance $attendance)
    {
        //
    }
}
