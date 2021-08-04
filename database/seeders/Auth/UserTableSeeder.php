<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Create an admin user.
        $cool_admin = User::query()->create([
            'first_name' => 'Cool',
            'middle_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@etm.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
            'remember_token' => Str::random(10),
        ]);

        // Create a default user.
        $default_user = User::query()->create([
            'first_name' => 'Not Bad',
            'middle_name' => 'Default',
            'last_name' => 'User',
            'email' => 'user@etm.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
            'remember_token' => Str::random(10),
        ]);

        $admin = Role::findByName('admin');
        $user = Role::findByName('user');

        $cool_admin->assignRole($admin);
        $default_user->assignRole($user);
    }
}
