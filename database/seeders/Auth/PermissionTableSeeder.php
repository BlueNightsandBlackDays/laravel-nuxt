<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    // User
    public static $USER_PERMISSIONS = [
        'view-any-user' => 'view any user',
        'create-user' => 'create user',
        'view-user' => 'view user',
        'update-user' => 'update user',
        'delete-user' => 'delete user',
    ];

    // Role
    public static $ROLE_PERMISSIONS = [
        'view-any-role' => 'view any role',
        'create-role' => 'create role',
        'view-role' => 'view role',
        'update-role' => 'update role',
        'delete-role' => 'delete role',
    ];

    // Attendance
    public static $ATTENDANCE_PERMISSIONS = [
        'view-any-attendance' => 'view any attendance',
        'create-attendance' => 'create attendance',
        'view-attendance' => 'view attendance',
        'update-attendance' => 'update attendance',
        'delete-attendance' => 'delete attendance',
    ];

    /**
     * Run the database seeds.
     *
     * @return array
     */
    public static function getAllPermissions(): array
    {
        return array_merge(
            self::$USER_PERMISSIONS,
            self::$ROLE_PERMISSIONS,
            self::$ATTENDANCE_PERMISSIONS,
        );
    }

    // Create Permissions
    public static function createPermissions(array $permissions)
    {
        // Create admin role
        $admin = Role::create([
            'name' => 'admin'
        ]);

        // Create default role
        $user = Role::create([
            'name' => 'user'
        ]);

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);

            // Give permission to admin
            $admin->givePermissionTo($permission);
        }

        // Give permission to default user
        $user->givePermissionTo(['view attendance', 'update attendance']);
    }
}
