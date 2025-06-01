<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lesson;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users for bookings
        $instructors = User::whereHas('role', function($query) {
            $query->where('name', 'instructor');
        })->get();

        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'customer');
        })->get();

        $lessons = Lesson::all();

        // Create some future bookings
        $startDate = Carbon::now();

        // Create 10 sample bookings
        for ($i = 1; $i <= 10; $i++) {
            $lesson = $lessons->random();
            $customer = $customers->random();
            $instructor = $instructors->random();

            // Generate a random future date within the next 30 days
            $bookingDate = $startDate->copy()->addDays(rand(1, 30));
            $startTime = Carbon::parse($bookingDate->format('Y-m-d') . ' ' . rand(9, 15) . ':00:00');
            $endTime = $startTime->copy()->addMinutes($lesson->duration);

            // Random number of participants (1 to max allowed)
            $participants = rand(1, $lesson->max_participants);

            // Create the booking
            Booking::create([
                'user_id' => $customer->id,
                'lesson_id' => $lesson->id,
                'instructor_id' => $instructor->id,
                'date' => $bookingDate->format('Y-m-d'),
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'status' => rand(0, 10) > 2 ? 'confirmed' : 'pending', // 80% chance to be confirmed
                'participants' => $participants,
                'total_price' => $lesson->price * $participants,
                'notes' => rand(0, 1) ? 'Special request: ' . ['Need wetsuit rental', 'Beginner needs extra attention', 'Bringing own equipment', 'Request for photos during lesson'][rand(0, 3)] : null,
            ]);
        }

        // Create some past bookings (marked as completed)
        for ($i = 1; $i <= 5; $i++) {
            $lesson = $lessons->random();
            $customer = $customers->random();
            $instructor = $instructors->random();

            // Generate a random past date within the last 30 days
            $bookingDate = $startDate->copy()->subDays(rand(1, 30));
            $startTime = Carbon::parse($bookingDate->format('Y-m-d') . ' ' . rand(9, 15) . ':00:00');
            $endTime = $startTime->copy()->addMinutes($lesson->duration);

            $participants = rand(1, $lesson->max_participants);

            // Create the completed booking
            Booking::create([
                'user_id' => $customer->id,
                'lesson_id' => $lesson->id,
                'instructor_id' => $instructor->id,
                'date' => $bookingDate->format('Y-m-d'),
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'status' => 'completed',
                'participants' => $participants,
                'total_price' => $lesson->price * $participants,
                'notes' => null,
            ]);
        }
    }
}
