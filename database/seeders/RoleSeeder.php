<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default roles
        Role::create([
            'name' => 'admin',
            'description' => 'Administrator with full access'
        ]);

        Role::create([
            'name' => 'instructor',
            'description' => 'Kitesurfing instructor'
        ]);

        Role::create([
            'name' => 'customer',
            'description' => 'Regular customer'
        ]);
    }
}
