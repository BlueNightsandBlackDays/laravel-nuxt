<?php

namespace App\Http\Controllers\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = User::class;
    protected $resource = UserResource::class;

    /**
     * Fills attributes on the given entity and stores it in database.
     *
     * @param Request $request
     * @param Model $entity
     * @param array $attributes
     */
    protected function performStore(Request $request, Model $entity, array $attributes): void
    {
        $attributes['password'] = bcrypt($attributes['password']);
        $this->performFill($request, $entity, $attributes);
        $entity->save();

        $entity->roles()->detach();
        foreach ($request->selected_roles as $role) {
            $entity->roles()->attach($role);
        }
    }

    /**
     * The hook is executed after updating a resource.
     *
     * @param Request $request
     * @param Model $entity
     * @return JsonResponse
     */
    protected function afterUpdate(Request $request, Model $entity): ?JsonResponse
    {
        $data = $request->get('selected_roles');
        if ($entity->hasRole($data)) {
            return response()->json('Role exist');
        } else {
            $roles = Role::query()->whereIn('name', $data)->get();
            $entity->assignRole($roles);
            return response()->json('Success');
        }
    }

    /**
     * The hook is executed before deleting a resource.
     *
     * @param Request $request,
     * @param Model $entity
     * @return void|JsonResponse
     */
    protected function beforeDestroy(Request $request, Model $entity)
    {
        if(auth()->user()->getAuthIdentifier() == $entity->id) {
            return response()->json('Cant_delete');
        }
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy() : array
    {
        return ['first_name', 'middle_name', 'options->key'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy() : array
    {
        return ['id', 'first_name', 'middle_name', 'options->visible'];
    }

    /**
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy() : array
    {
        return ['id', 'first_name', 'middle_name', 'options->key', 'meta.priority'];
    }
}
