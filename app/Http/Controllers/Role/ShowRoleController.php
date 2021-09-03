<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ShowRoleController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        if(auth()->user()->isadmin()) {
            $user = User::query()->where('id', $id)->first();
            $role = $user->getRoleNames();
            return response()->json($role);
        }
        return response()->json('Unauthorized');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param string $roles
     * @return JsonResponse
     */
    public function revoke(int $id, string $roles): JsonResponse
    {
        if(auth()->user()->isadmin()) {
            if(auth()->user()->getAuthIdentifier() == $id) {
                return response()->json('Can\'t revoked');
            } else {
                $user = User::query()->where('id', $id)->first();
                $role = Role::findByName($roles);
                $user->removeRole($role);
                return response()->json('revoked');
            }
        }
        return response()->json('Unauthorized');
    }
}
