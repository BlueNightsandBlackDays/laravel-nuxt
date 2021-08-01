<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\User\UpdateUserPasswordRequest;

class UpdatePasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param User $user
     * @param UpdateUserPasswordRequest $request
     * @return bool
     */
    public function update(UpdateUserPasswordRequest $request, User $user): bool
    {
        return $user->update([
            'password' => bcrypt($request['password']),
        ]);
    }
}
