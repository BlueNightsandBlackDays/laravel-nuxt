<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Database\Seeders\Auth\PermissionTableSeeder;
use Database\Seeders\Auth\UserTableSeeder;
use Database\Seeders\Auth\UserPermissionTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // \App\Models\User::factory(10)->create();
        $permission = PermissionTableSeeder::getAllPermissions();
        PermissionTableSeeder::createPermissions($permission);

        $this->call([
            UserTableSeeder::class,
            UserPermissionTableSeeder::class
        ]);
    }
}
