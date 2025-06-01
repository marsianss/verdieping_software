<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\Lesson;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class InstructorControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $instructor;
    protected $customer;
    protected $lesson;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $instructorRole = Role::create(['name' => 'instructor']);
        $customerRole = Role::create(['name' => 'customer']);

        // Create users
        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->instructor = User::factory()->create(['role_id' => $instructorRole->id]);
        $this->customer = User::factory()->create(['role_id' => $customerRole->id]);

        // Create a lesson
        $this->lesson = Lesson::factory()->create();
    }

    /** @test */
    public function instructor_can_access_dashboard()
    {
        $response = $this->actingAs($this->instructor)
            ->get(route('instructor.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('instructor.dashboard');
    }

    /** @test */
    public function instructor_dashboard_shows_upcoming_bookings()
    {
        $booking = Booking::factory()->create([
            'instructor_id' => $this->instructor->id,
            'user_id' => $this->customer->id,
            'lesson_id' => $this->lesson->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->instructor)
            ->get(route('instructor.dashboard'));

        $response->assertStatus(200);
        $response->assertSee($this->customer->name);
        $response->assertSee($this->lesson->name);
    }

    /** @test */
    public function instructor_can_view_their_schedule()
    {
        $booking = Booking::factory()->create([
            'instructor_id' => $this->instructor->id,
            'user_id' => $this->customer->id,
            'lesson_id' => $this->lesson->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'start_time' => '14:00:00',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->instructor)
            ->get(route('instructor.schedule'));

        $response->assertStatus(200);
        $response->assertViewIs('instructor.schedule');
        $response->assertSee($this->customer->name);
        $response->assertSee('14:00');
    }

    /** @test */
    public function instructor_can_mark_booking_as_completed()
    {
        $booking = Booking::factory()->create([
            'instructor_id' => $this->instructor->id,
            'user_id' => $this->customer->id,
            'lesson_id' => $this->lesson->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->instructor)
            ->patch(route('instructor.bookings.status', $booking), [
                'status' => 'completed'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('completed', $booking->status);
    }

    /** @test */
    public function instructor_can_mark_booking_as_cancelled()
    {
        $booking = Booking::factory()->create([
            'instructor_id' => $this->instructor->id,
            'user_id' => $this->customer->id,
            'lesson_id' => $this->lesson->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->instructor)
            ->patch(route('instructor.bookings.status', $booking), [
                'status' => 'cancelled'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
    }

    /** @test */
    public function instructor_can_confirm_pending_booking()
    {
        $booking = Booking::factory()->create([
            'instructor_id' => $this->instructor->id,
            'user_id' => $this->customer->id,
            'lesson_id' => $this->lesson->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->instructor)
            ->patch(route('instructor.bookings.status', $booking), [
                'status' => 'confirmed'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status);
    }

    /** @test */
    public function instructor_cannot_update_other_instructors_bookings()
    {
        $otherInstructor = User::factory()->create(['role_id' => 2]);
        $booking = Booking::factory()->create([
            'instructor_id' => $otherInstructor->id,
            'user_id' => $this->customer->id,
            'lesson_id' => $this->lesson->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->instructor)
            ->patch(route('instructor.bookings.status', $booking), [
                'status' => 'completed'
            ]);

        $response->assertStatus(403); // Forbidden

        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status); // Should remain unchanged
    }

    /** @test */
    public function instructor_booking_status_validation_includes_completed()
    {
        $booking = Booking::factory()->create([
            'instructor_id' => $this->instructor->id,
            'user_id' => $this->customer->id,
            'lesson_id' => $this->lesson->id,
            'status' => 'confirmed',
        ]);

        // Test that 'completed' is now a valid status (this was our fix)
        $response = $this->actingAs($this->instructor)
            ->patch(route('instructor.bookings.status', $booking), [
                'status' => 'completed'
            ]);

        $response->assertRedirect(); // Should not have validation errors
        $response->assertSessionMissing('errors');

        $booking->refresh();
        $this->assertEquals('completed', $booking->status);
    }

    /** @test */
    public function instructor_booking_status_validation_rejects_invalid_status()
    {
        $booking = Booking::factory()->create([
            'instructor_id' => $this->instructor->id,
            'user_id' => $this->customer->id,
            'lesson_id' => $this->lesson->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->instructor)
            ->patch(route('instructor.bookings.status', $booking), [
                'status' => 'invalid_status'
            ]);

        $response->assertSessionHasErrors(['status']);

        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status); // Should remain unchanged
    }

    /** @test */
    public function customer_cannot_access_instructor_dashboard()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('instructor.dashboard'));

        $response->assertStatus(403); // Forbidden
    }

    /** @test */
    public function guest_cannot_access_instructor_dashboard()
    {
        $response = $this->get(route('instructor.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function instructor_dashboard_shows_correct_statistics()
    {
        // Create various bookings for this instructor
        Booking::factory()->create([
            'instructor_id' => $this->instructor->id,
            'status' => 'pending',
        ]);

        Booking::factory()->create([
            'instructor_id' => $this->instructor->id,
            'status' => 'confirmed',
        ]);

        Booking::factory()->create([
            'instructor_id' => $this->instructor->id,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->instructor)
            ->get(route('instructor.dashboard'));

        $response->assertStatus(200);

        // Should show total bookings count
        $response->assertSee('3'); // Total bookings

        // Should show pending bookings count
        $response->assertSee('1'); // Pending bookings
    }

    /** @test */
    public function instructor_can_view_today_bookings()
    {
        $todayBooking = Booking::factory()->create([
            'instructor_id' => $this->instructor->id,
            'user_id' => $this->customer->id,
            'lesson_id' => $this->lesson->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'start_time' => '10:00:00',
            'status' => 'confirmed',
        ]);

        $tomorrowBooking = Booking::factory()->create([
            'instructor_id' => $this->instructor->id,
            'user_id' => $this->customer->id,
            'lesson_id' => $this->lesson->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '14:00:00',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->instructor)
            ->get(route('instructor.dashboard'));

        $response->assertStatus(200);

        // Today's booking should be prominently displayed
        $response->assertSee('10:00');
        // Tomorrow's booking might also be shown in upcoming section
        $response->assertSee('14:00');
    }

    /** @test */
    public function instructor_bookings_page_shows_all_instructor_bookings()
    {
        $bookings = Booking::factory()->count(5)->create([
            'instructor_id' => $this->instructor->id,
            'user_id' => $this->customer->id,
            'lesson_id' => $this->lesson->id,
        ]);

        $response = $this->actingAs($this->instructor)
            ->get(route('instructor.bookings'));

        $response->assertStatus(200);
        $response->assertViewIs('instructor.bookings');

        // Should show all instructor's bookings
        foreach ($bookings as $booking) {
            $response->assertSee($booking->lesson->name);
        }
    }

    /** @test */
    public function instructor_can_see_booking_details_in_schedule()
    {
        $booking = Booking::factory()->create([
            'instructor_id' => $this->instructor->id,
            'user_id' => $this->customer->id,
            'lesson_id' => $this->lesson->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'start_time' => '15:30:00',
            'end_time' => '17:30:00',
            'participants' => 3,
            'notes' => 'Beginner group lesson',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->instructor)
            ->get(route('instructor.schedule'));

        $response->assertStatus(200);
        $response->assertSee($this->customer->name);
        $response->assertSee($this->lesson->name);
        $response->assertSee('15:30');
        $response->assertSee('17:30');
        $response->assertSee('3'); // participants
        $response->assertSee('Beginner group lesson');
        $response->assertSee('confirmed');
    }
}
