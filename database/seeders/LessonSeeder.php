<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample kitesurfing lessons
        Lesson::create([
            'name' => 'Beginners Introduction',
            'description' => 'An introduction to kitesurfing for complete beginners. Learn the basics of equipment, safety, and basic techniques.',
            'duration' => 120, // 2 hours
            'price' => 89.99,
            'max_participants' => 4,
            'difficulty_level' => 'beginner'
        ]);

        Lesson::create([
            'name' => 'Intermediate Skills',
            'description' => 'For those who have mastered the basics. Focus on improving your technique, riding upwind, and performing basic jumps.',
            'duration' => 180, // 3 hours
            'price' => 129.99,
            'max_participants' => 3,
            'difficulty_level' => 'intermediate'
        ]);

        Lesson::create([
            'name' => 'Advanced Techniques',
            'description' => 'For experienced kitesurfers looking to master advanced tricks, jumps, and wave riding techniques.',
            'duration' => 240, // 4 hours
            'price' => 179.99,
            'max_participants' => 2,
            'difficulty_level' => 'advanced'
        ]);

        Lesson::create([
            'name' => 'Private Lesson',
            'description' => 'One-on-one instruction tailored to your specific skill level and goals.',
            'duration' => 120, // 2 hours
            'price' => 149.99,
            'max_participants' => 1,
            'difficulty_level' => 'any'
        ]);

        Lesson::create([
            'name' => 'Group Weekend Package',
            'description' => 'A full weekend of kitesurfing lessons for a group, including equipment rental.',
            'duration' => 480, // 8 hours over the weekend
            'price' => 249.99,
            'max_participants' => 6,
            'difficulty_level' => 'beginner'
        ]);
    }
}