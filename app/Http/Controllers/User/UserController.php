<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Requests\User\StoreUserRequest;

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
     * @return JsonResponse
     */
    public function index(): JsonResponse
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
        $data = $request->get('selected_roles');
        $users = User::query()->create([
            'first_name' => ucfirst($request['first_name']),
            'middle_name' => ucfirst($request['middle_name']),
            'last_name' => ucfirst($request['last_name']),
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        $roles = Role::query()->whereIn('name', $data)->get();
        $users->syncRoles($roles);

        return response()->json($users);
    }

    /**
     * Display the specified resource.
     *
     */
    public function show()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy()
    {
        //
    }
}
