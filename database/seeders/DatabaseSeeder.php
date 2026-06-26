<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);
        $this->call(MenuSeeder::class);

        $admin = User::query()->firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Super Admin',
            'email_verified_at' => Carbon::now(),
            'password' => 'password',
        ]);

        $admin->syncRoles('super-admin');
    }
}
