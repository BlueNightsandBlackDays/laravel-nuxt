<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;

class FetchPermissions extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = User::query()->where('id', $id)->first();
        $permissions = $user->getPermissionsViaRoles()->pluck('name');
        return response()->json($permissions);
    }
}
