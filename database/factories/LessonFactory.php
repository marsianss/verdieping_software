<?php

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        $lessons = [
            [
                'name' => 'Beginner Kitesurfing Course',
                'description' => 'Perfect introduction to kitesurfing with safety briefing and basic techniques.',
                'duration' => 120,
                'price' => 89.00,
                'difficulty_level' => 'beginner'
            ],
            [
                'name' => 'Intermediate Skills Development',
                'description' => 'Build on your foundation with advanced maneuvers and water start techniques.',
                'duration' => 90,
                'price' => 129.00,
                'difficulty_level' => 'intermediate'
            ],
            [
                'name' => 'Advanced Freestyle Session',
                'description' => 'Master advanced tricks and freestyle techniques with expert guidance.',
                'duration' => 60,
                'price' => 179.00,
                'difficulty_level' => 'advanced'
            ],
            [
                'name' => 'Private One-on-One Lesson',
                'description' => 'Personalized instruction tailored to your specific needs and goals.',
                'duration' => 90,
                'price' => 199.00,
                'difficulty_level' => 'beginner'
            ]
        ];

        $lesson = $this->faker->randomElement($lessons);

        return [
            'name' => $lesson['name'],
            'description' => $lesson['description'],
            'duration' => $lesson['duration'],
            'price' => $lesson['price'],
            'max_participants' => $this->faker->numberBetween(1, 6),
            'difficulty_level' => $lesson['difficulty_level'],
        ];
    }

    public function beginner()
    {
        return $this->state(fn (array $attributes) => [
            'difficulty_level' => 'beginner',
            'price' => $this->faker->randomFloat(2, 75, 120),
        ]);
    }

    public function intermediate()
    {
        return $this->state(fn (array $attributes) => [
            'difficulty_level' => 'intermediate',
            'price' => $this->faker->randomFloat(2, 120, 180),
        ]);
    }

    public function advanced()
    {
        return $this->state(fn (array $attributes) => [
            'difficulty_level' => 'advanced',
            'price' => $this->faker->randomFloat(2, 180, 250),
        ]);
    }
}
