<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class RevokeRoleController extends Controller
{
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
