<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Booking;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class BookingModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'instructor']);
        Role::create(['name' => 'customer']);
    }

    /** @test */
    public function it_can_create_a_booking()
    {
        $customer = User::factory()->create(['role_id' => 3]);
        $instructor = User::factory()->create(['role_id' => 2]);
        $lesson = Lesson::factory()->create();

        $booking = Booking::create([
            'user_id' => $customer->id,
            'lesson_id' => $lesson->id,
            'instructor_id' => $instructor->id,
            'date' => '2025-06-15',
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'status' => 'confirmed',
            'participants' => 2,
            'total_price' => 150.00,
            'notes' => 'Test booking'
        ]);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertEquals('2025-06-15', $booking->date->format('Y-m-d'));
        $this->assertEquals('14:00:00', $booking->start_time);
        $this->assertEquals('16:00:00', $booking->end_time);
        $this->assertEquals('confirmed', $booking->status);
    }

    /** @test */
    public function it_casts_date_properly()
    {
        $booking = Booking::factory()->create([
            'date' => '2025-06-15'
        ]);

        $this->assertInstanceOf(Carbon::class, $booking->date);
        $this->assertEquals('2025-06-15', $booking->date->format('Y-m-d'));
    }

    /** @test */
    public function it_does_not_cast_time_fields_as_datetime()
    {
        $booking = Booking::factory()->create([
            'start_time' => '14:00:00',
            'end_time' => '16:00:00'
        ]);

        // Time fields should remain as strings, not be cast to datetime
        $this->assertIsString($booking->start_time);
        $this->assertIsString($booking->end_time);
        $this->assertEquals('14:00:00', $booking->start_time);
        $this->assertEquals('16:00:00', $booking->end_time);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $booking->user);
        $this->assertEquals($user->id, $booking->user->id);
    }

    /** @test */
    public function it_belongs_to_a_lesson()
    {
        $lesson = Lesson::factory()->create();
        $booking = Booking::factory()->create(['lesson_id' => $lesson->id]);

        $this->assertInstanceOf(Lesson::class, $booking->lesson);
        $this->assertEquals($lesson->id, $booking->lesson->id);
    }

    /** @test */
    public function it_belongs_to_an_instructor()
    {
        $instructor = User::factory()->create();
        $booking = Booking::factory()->create(['instructor_id' => $instructor->id]);

        $this->assertInstanceOf(User::class, $booking->instructor);
        $this->assertEquals($instructor->id, $booking->instructor->id);
    }

    /** @test */
    public function it_can_handle_different_status_values()
    {
        $statuses = ['pending', 'confirmed', 'cancelled', 'completed'];

        foreach ($statuses as $status) {
            $booking = Booking::factory()->create(['status' => $status]);
            $this->assertEquals($status, $booking->status);
        }
    }

    /** @test */
    public function it_can_store_decimal_prices()
    {
        $booking = Booking::factory()->create(['total_price' => 125.50]);

        $this->assertEquals(125.50, $booking->total_price);
        $this->assertIsFloat($booking->total_price);
    }

    /** @test */
    public function it_can_handle_null_notes()
    {
        $booking = Booking::factory()->create(['notes' => null]);

        $this->assertNull($booking->notes);
    }

    /** @test */
    public function it_can_handle_participants_count()
    {
        $booking = Booking::factory()->create(['participants' => 5]);

        $this->assertEquals(5, $booking->participants);
        $this->assertIsInt($booking->participants);
    }

    /** @test */
    public function date_parsing_works_correctly_with_time_concatenation()
    {
        $booking = Booking::factory()->create([
            'date' => '2025-06-04',
            'start_time' => '14:00:00'
        ]);

        // Test the fix for date parsing that was causing errors
        $dateTimeString = $booking->date->format('Y-m-d') . ' ' . $booking->start_time;
        $parsedDateTime = Carbon::parse($dateTimeString);

        $this->assertEquals('2025-06-04 14:00:00', $parsedDateTime->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function booking_fillable_attributes_are_correct()
    {
        $booking = new Booking();
        $expectedFillable = [
            'user_id',
            'lesson_id',
            'instructor_id',
            'date',
            'start_time',
            'end_time',
            'status',
            'participants',
            'total_price',
            'notes'
        ];

        $this->assertEquals($expectedFillable, $booking->getFillable());
    }    /** @test */
    public function booking_casts_are_correct()
    {
        $booking = new Booking();
        $expectedCasts = [
            'date' => 'date',
            'id' => 'int', // Laravel automatically casts ID to int
        ];

        $this->assertEquals($expectedCasts, $booking->getCasts());
    }
}
