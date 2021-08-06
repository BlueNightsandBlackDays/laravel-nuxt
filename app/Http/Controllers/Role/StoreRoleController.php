<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
//use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class StoreRoleController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRoleRequest $request
     * @return JsonResponse
     */
    protected function Store(StoreRoleRequest $request): JsonResponse
    {
        $data = $request->get('selected_permissions');
        $role = Role::create([
            'name' => strtolower($request['name'])
        ]);

        $permissions = Permission::query()->whereIn('id', $data)->get()->pluck('name', 'name');
        foreach ($permissions as $permission) {

            // Give permission to role
            $role->givePermissionTo($permission);
        }


        return response()->json($permissions);
    }
}
