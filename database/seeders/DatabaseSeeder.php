<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in the correct order to handle dependencies
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            LessonSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
