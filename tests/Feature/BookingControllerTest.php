<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\Lesson;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class BookingControllerTest extends TestCase
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
    public function customer_can_view_their_bookings()
    {
        $booking = Booking::factory()->create([
            'user_id' => $this->customer->id,
            'instructor_id' => $this->instructor->id,
            'lesson_id' => $this->lesson->id,
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('bookings.index'));

        $response->assertStatus(200);
        $response->assertSee($booking->lesson->name);
    }

    /** @test */
    public function customer_can_view_specific_booking()
    {
        $booking = Booking::factory()->create([
            'user_id' => $this->customer->id,
            'instructor_id' => $this->instructor->id,
            'lesson_id' => $this->lesson->id,
            'date' => '2025-06-15',
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('bookings.show', $booking));

        $response->assertStatus(200);
        $response->assertSee($booking->lesson->name);
        $response->assertSee('15-06-2025'); // Check date formatting
        $response->assertSee('14:00'); // Check time display
    }

    /** @test */
    public function booking_show_page_displays_date_and_time_correctly()
    {
        $booking = Booking::factory()->create([
            'user_id' => $this->customer->id,
            'instructor_id' => $this->instructor->id,
            'lesson_id' => $this->lesson->id,
            'date' => '2025-06-04',
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('bookings.show', $booking));

        $response->assertStatus(200);

        // Test that the date parsing fix works correctly
        // The view should display the formatted date and time without errors
        $response->assertSee('04-06-2025');
        $response->assertSee('14:00');
        $response->assertSee('16:00');
    }

    /** @test */
    public function customer_cannot_view_other_customers_bookings()
    {
        $otherCustomer = User::factory()->create(['role_id' => 3]);
        $booking = Booking::factory()->create([
            'user_id' => $otherCustomer->id,
            'instructor_id' => $this->instructor->id,
            'lesson_id' => $this->lesson->id,
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('bookings.show', $booking));

        $response->assertStatus(403); // Forbidden
    }

    /** @test */
    public function customer_can_create_new_booking()
    {
        $bookingData = [
            'lesson_id' => $this->lesson->id,
            'instructor_id' => $this->instructor->id,
            'date' => '2025-06-20',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'participants' => 2,
            'notes' => 'Looking forward to the lesson!',
        ];

        $response = $this->actingAs($this->customer)
            ->post(route('bookings.store'), $bookingData);

        $response->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->customer->id,
            'lesson_id' => $this->lesson->id,
            'instructor_id' => $this->instructor->id,
            'date' => '2025-06-20',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'status' => 'pending',
            'participants' => 2,
            'notes' => 'Looking forward to the lesson!',
        ]);
    }

    /** @test */
    public function customer_can_cancel_their_booking()
    {
        $booking = Booking::factory()->create([
            'user_id' => $this->customer->id,
            'instructor_id' => $this->instructor->id,
            'lesson_id' => $this->lesson->id,
            'status' => 'confirmed',
            'date' => Carbon::now()->addDays(2)->format('Y-m-d'), // Future date for cancellation
        ]);

        $response = $this->actingAs($this->customer)
            ->patch(route('bookings.cancel', $booking));

        $response->assertRedirect();

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
    }

    /** @test */
    public function customer_cannot_cancel_booking_within_24_hours()
    {
        $booking = Booking::factory()->create([
            'user_id' => $this->customer->id,
            'instructor_id' => $this->instructor->id,
            'lesson_id' => $this->lesson->id,
            'status' => 'confirmed',
            'date' => Carbon::now()->addHours(12)->format('Y-m-d'), // Less than 24 hours
            'start_time' => Carbon::now()->addHours(12)->format('H:i:s'),
        ]);

        $response = $this->actingAs($this->customer)
            ->patch(route('bookings.cancel', $booking));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status); // Should remain unchanged
    }

    /** @test */
    public function booking_validation_works_correctly()
    {
        $invalidData = [
            'lesson_id' => '', // Required field missing
            'instructor_id' => $this->instructor->id,
            'date' => 'invalid-date',
            'start_time' => '25:00:00', // Invalid time
            'participants' => 0, // Should be at least 1
        ];

        $response = $this->actingAs($this->customer)
            ->post(route('bookings.store'), $invalidData);

        $response->assertSessionHasErrors(['lesson_id', 'date', 'start_time', 'participants']);
    }

    /** @test */
    public function booking_cannot_be_made_for_past_date()
    {
        $pastDate = Carbon::yesterday()->format('Y-m-d');

        $bookingData = [
            'lesson_id' => $this->lesson->id,
            'instructor_id' => $this->instructor->id,
            'date' => $pastDate,
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'participants' => 1,
        ];

        $response = $this->actingAs($this->customer)
            ->post(route('bookings.store'), $bookingData);

        $response->assertSessionHasErrors(['date']);
    }

    /** @test */
    public function guest_cannot_access_bookings()
    {
        $response = $this->get(route('bookings.index'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function booking_shows_correct_pricing_calculation()
    {
        $lesson = Lesson::factory()->create(['price' => 50.00]);

        $bookingData = [
            'lesson_id' => $lesson->id,
            'instructor_id' => $this->instructor->id,
            'date' => '2025-06-20',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'participants' => 3,
        ];

        $response = $this->actingAs($this->customer)
            ->post(route('bookings.store'), $bookingData);

        $booking = Booking::where('user_id', $this->customer->id)->first();
        $expectedPrice = $lesson->price * $bookingData['participants'];

        $this->assertEquals($expectedPrice, $booking->total_price);
    }

    /** @test */
    public function booking_index_shows_upcoming_bookings_first()
    {
        // Create a past booking
        $pastBooking = Booking::factory()->create([
            'user_id' => $this->customer->id,
            'date' => Carbon::yesterday()->format('Y-m-d'),
        ]);

        // Create a future booking
        $futureBooking = Booking::factory()->create([
            'user_id' => $this->customer->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('bookings.index'));

        $response->assertStatus(200);

        // The future booking should appear before the past booking in the response
        $content = $response->getContent();
        $futurePos = strpos($content, $futureBooking->id);
        $pastPos = strpos($content, $pastBooking->id);

        $this->assertTrue($futurePos < $pastPos);
    }
}
