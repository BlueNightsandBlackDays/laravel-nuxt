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
    public function showRole(int $id): JsonResponse
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
     * @param  int $id
     * @return JsonResponse
     */
    public function showPermission(int $id): JsonResponse
    {
        if(auth()->user()->isadmin()) {
            $role = Role::query()->where('id', $id)->first();
            $permissions = $role->getAllPermissions()->pluck('name', 'name');
            return response()->json($permissions);
        }
        return response()->json('Unauthorized');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param string $permission
     * @return JsonResponse
     */
    public function revokePermission(int $id, string $permission): JsonResponse
    {
        if(auth()->user()->isadmin()) {
            $role = Role::findById($id);
            $role->revokePermissionTo($permission);
            return response()->json('revoked');
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
    public function revokeRole(int $id, string $roles): JsonResponse
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
