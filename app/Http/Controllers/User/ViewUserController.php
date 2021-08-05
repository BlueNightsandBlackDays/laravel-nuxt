<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\user;
use App\Http\Resources\UserResource;

class ViewUserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }
}
