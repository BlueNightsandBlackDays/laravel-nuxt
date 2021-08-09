<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Http\Requests\User\UpdateUserPasswordRequest;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class UpdateUserController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        if(auth()->user()->isadmin()) {
            $data = $request->get('selected_roles');

            if ($user->hasRole($data)) {
                return response()->json('Role exist');
            } else {
                $users = $user->update($request->validated());
                $roles = Role::query()->whereIn('name', $data)->get();
                $user->assignRole($roles);
                return response()->json($users);
            }
        }
        return response()->json('Unauthorized');
    }

    /**
     * Update the user's password.
     *
     * @param User $user
     * @param UpdateUserPasswordRequest $request
     * @return bool
     */
    public function updatePass(UpdateUserPasswordRequest $request, User $user): bool
    {
        if(auth()->user()->isadmin()) {
            return $user->update([
                'password' => bcrypt($request['password']),
            ]);
        }
        return false;
    }
}
