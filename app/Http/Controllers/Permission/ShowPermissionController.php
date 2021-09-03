<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class ShowPermissionController extends Controller
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
    public function revoke(int $id, string $permission): JsonResponse
    {
        if(auth()->user()->isadmin()) {
            $role = Role::findById($id);
            $role->revokePermissionTo($permission);
            return response()->json('revoked');
        }
        return response()->json('Unauthorized');
    }
}
