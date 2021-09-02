<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\User\UpdateUserPasswordRequest;
use App\Models\User;

class UpdatePasswordController extends Controller
{

    /**
     * Update the user's password.
     *
     * @param User $user
     * @param UpdateUserPasswordRequest $request
     * @return JsonResponse
     */
    public function update(UpdateUserPasswordRequest $request, User $user): JsonResponse
    {
        if(auth()->user()->isadmin()) {
            $user->update([
                'password' => bcrypt($request['password']),
            ]);

            return response()->json('Password updated');
        }
        return response()->json('Unauthorized');
    }
}
