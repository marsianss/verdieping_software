<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get role IDs
        $adminRole = Role::where('name', 'admin')->first();
        $instructorRole = Role::where('name', 'instructor')->first();
        $customerRole = Role::where('name', 'customer')->first();

        // Create Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@windkracht12.nl',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'phone' => '+31612345678',
            'address' => 'Strandweg 1, Den Haag',
            'experience_level' => 'expert'
        ]);

        // Create Instructor users
        User::create([
            'name' => 'Jan Instructeur',
            'email' => 'jan@windkracht12.nl',
            'password' => Hash::make('password'),
            'role_id' => $instructorRole->id,
            'phone' => '+31623456789',
            'address' => 'Zeeweg 10, Scheveningen',
            'experience_level' => 'expert'
        ]);

        User::create([
            'name' => 'Lisa Leraar',
            'email' => 'lisa@windkracht12.nl',
            'password' => Hash::make('password'),
            'role_id' => $instructorRole->id,
            'phone' => '+31634567890',
            'address' => 'Duinpad 5, Katwijk',
            'experience_level' => 'expert'
        ]);

        // Create Customer users
        User::create([
            'name' => 'Peter Klant',
            'email' => 'peter@example.com',
            'password' => Hash::make('password'),
            'role_id' => $customerRole->id,
            'phone' => '+31645678901',
            'address' => 'Hoofdstraat 123, Amsterdam',
            'experience_level' => 'beginner'
        ]);

        User::create([
            'name' => 'Sandra Student',
            'email' => 'sandra@example.com',
            'password' => Hash::make('password'),
            'role_id' => $customerRole->id,
            'phone' => '+31656789012',
            'address' => 'Kerkstraat 45, Rotterdam',
            'experience_level' => 'intermediate'
        ]);

        User::create([
            'name' => 'Martijn Meer',
            'email' => 'martijn@example.com',
            'password' => Hash::make('password'),
            'role_id' => $customerRole->id,
            'phone' => '+31667890123',
            'address' => 'Bergweg 78, Utrecht',
            'experience_level' => 'beginner'
        ]);
    }
}
