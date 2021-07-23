<?php

namespace Database\Seeders;

use Database\Seeders\Auth\PermissionTableSeeder;
use Database\Seeders\Auth\UserTableSeeder;
use Database\Seeders\Auth\RoleTableSeeder;
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
        $this->call([
            PermissionTableSeeder::class,
            UserTableSeeder::class,
            RoleTableSeeder::class,
        ]);
    }
}
