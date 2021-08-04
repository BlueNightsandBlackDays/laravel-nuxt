<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::query()->find(1);
        $permissions = Permission::all();

        foreach ($permissions as $permission) {

            // Give permission to admin
            $admin->givePermissionTo($permission);
        }
    }
}
