<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\JsonResponse;
use Orion\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Orion\Http\Requests\Request;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    protected $model = Role::class;

    /**
     * The hook is executed after creating new resource.
     *
     * @param Request $request
     * @param Model $entity
     */
    protected function afterStore(Request $request, Model $entity)
    {
        $entity->permissions()->detach();
        foreach ($request->selected_permissions as $permission) {
            $entity->givePermissionTo($permission);
        }
    }

    /**
     * The hook is executed after creating new resource.
     *
     * @param Request $request
     * @param Model $entity
     * @return void|JsonResponse
     */
    protected function afterUpdate(Request $request, Model $entity)
    {
        $data = $request->get('selected_permissions');
        if ($entity->hasAnyDirectPermission($data)) {
            return response()->json('Permission Exist');
        } else {
            $permissions = Permission::query()->whereIn('id', $data)->get()->pluck('name', 'name');
            foreach ($permissions as $permission) {

                // Give permission to role
                $entity->givePermissionTo($permission);
            }
        }
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy() : array
    {
        return ['name'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy() : array
    {
        return ['id', 'name'];
    }

    /**
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy() : array
    {
        return ['id', 'name'];
    }
}
