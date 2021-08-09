<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\user;
use App\Http\Resources\UserResource;

class ViewUserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return UserResource | JsonResponse
     */
    public function show(User $user)
    {
        if(auth()->user()->isadmin()) {
            return new UserResource($user);
        }
        return response()->json('Unauthorized');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        if(auth()->user()->isadmin()) {
            if(auth()->user()->getAuthIdentifier() == $user->id) {
                return response()->json('Can\'t delete');
            }
            $user->delete();
            return response()->json('deleted');
        }
        return response()->json('Unauthorized');
    }
}
