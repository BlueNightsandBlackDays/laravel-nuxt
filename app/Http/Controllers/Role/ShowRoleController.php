<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\UpdateRoleRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Spatie\Permission\Models\Permission;
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
     * @param  UpdateRoleRequest $request
     * @param  int $id
     * @return JsonResponse
     */
    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        if(auth()->user()->isadmin()) {
            $roles = Role::findById($id);
            $data = $request->get('selected_permissions');
            if ($roles->hasAnyDirectPermission($data)) {
                return response()->json('Permission Exist');
            } else {
                $role = Role::query()->where('id', $id)->update([
                    'name' => strtolower($request['name'])
                ]);

                $permissions = Permission::query()->whereIn('id', $data)->get()->pluck('name', 'name');
                foreach ($permissions as $permission) {

                    // Give permission to role
                    $roles->givePermissionTo($permission);
                }
                return response()->json($role);
            }
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
