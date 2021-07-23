<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create roles
        $admin = Role::create([
            'name' => 'admin_role'
        ]);
        $user = Role::create([
            'name' => 'default_role'
        ]);

        $permissions = [
            // User
            'view-any-user' => 'view any user',
            'create-user' => 'create user',
            'view-user' => 'view user',
            'update-user' => 'update user',
            'delete-user' => 'delete user',
            'restore-user' => 'restore user',
            'force-delete-user' => 'force delete user',
            'impersonate-user' => 'impersonate user',

            // Role
            'view-any-role' => 'view any role',
            'create-role' => 'create role',
            'view-role' => 'view role',
            'update-role' => 'update role',
            'delete-role' => 'delete role',
            'restore-role' => 'restore role',
            'force-delete-role' => 'force delete role',

        ];

        foreach ($permissions as $permission) {
            // Create Permissions
            Permission::create([
                'name' => $permission
            ]);

            $admin->givePermissionTo($permission);
        }
    }
}
