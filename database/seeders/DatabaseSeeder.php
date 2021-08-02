<?php

namespace Database\Seeders;

use Database\Seeders\Auth\PermissionTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $permission = PermissionTableSeeder::getAllPermissions();
        PermissionTableSeeder::createPermissions($permission);
    }
}