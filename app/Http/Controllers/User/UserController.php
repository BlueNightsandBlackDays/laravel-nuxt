<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
//use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'users');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $users = User::query()->orderBy('id')->simplePaginate(10);
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $users = User::query()->create([
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        $user = \Spatie\Permission\Models\Role::findByName('user');
        $users->assignRole($user);

        return response()->json($users);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     */
    public function show(int $id)
    {
        //return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return bool
     */
    public function update(UpdateUserRequest $request, User $user): bool
    {
        return $user->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        if(auth()->user()->getAuthIdentifier() == $user->id) {
            return response()->json('Can\'t delete');
        }

        $user->delete();
        return response()->json('deleted');
    }
}
