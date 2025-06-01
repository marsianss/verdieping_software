<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Attributes\Test;

class UserModelTest extends TestCase
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

    #[Test]
    public function it_has_many_bookings()
    {
        $user = User::factory()->create();
        Booking::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Collection::class, $user->bookings);
        $this->assertCount(3, $user->bookings);
        $this->assertInstanceOf(Booking::class, $user->bookings->first());
    }

    #[Test]
    public function it_has_many_instructor_bookings()
    {
        $instructor = User::factory()->create(['role_id' => 2]); // instructor role
        Booking::factory()->count(2)->create(['instructor_id' => $instructor->id]);

        $this->assertInstanceOf(Collection::class, $instructor->instructorBookings);
        $this->assertCount(2, $instructor->instructorBookings);
        $this->assertInstanceOf(Booking::class, $instructor->instructorBookings->first());
    }

    #[Test]
    public function instructor_bookings_relationship_uses_correct_foreign_key()
    {
        $instructor = User::factory()->create();
        $booking = Booking::factory()->create(['instructor_id' => $instructor->id]);

        $instructorBookings = $instructor->instructorBookings;
        $this->assertTrue($instructorBookings->contains($booking));
        $this->assertEquals($instructor->id, $instructorBookings->first()->instructor_id);
    }

    #[Test]
    public function it_belongs_to_a_role()
    {
        $role = Role::first();
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->assertInstanceOf(Role::class, $user->role);
        $this->assertEquals($role->id, $user->role->id);
    }

    #[Test]
    public function is_admin_returns_true_for_admin_role()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $user = User::factory()->create(['role_id' => $adminRole->id]);

        $this->assertTrue($user->isAdmin());
    }

    #[Test]
    public function is_admin_returns_false_for_non_admin_role()
    {
        $customerRole = Role::where('name', 'customer')->first();
        $user = User::factory()->create(['role_id' => $customerRole->id]);

        $this->assertFalse($user->isAdmin());
    }

    #[Test]
    public function is_instructor_returns_true_for_instructor_role()
    {
        $instructorRole = Role::where('name', 'instructor')->first();
        $user = User::factory()->create(['role_id' => $instructorRole->id]);

        $this->assertTrue($user->isInstructor());
    }

    #[Test]
    public function is_instructor_returns_false_for_non_instructor_role()
    {
        $customerRole = Role::where('name', 'customer')->first();
        $user = User::factory()->create(['role_id' => $customerRole->id]);

        $this->assertFalse($user->isInstructor());
    }

    #[Test]
    public function is_customer_returns_true_for_customer_role()
    {
        $customerRole = Role::where('name', 'customer')->first();
        $user = User::factory()->create(['role_id' => $customerRole->id]);

        $this->assertTrue($user->isCustomer());
    }

    #[Test]
    public function is_customer_returns_false_for_non_customer_role()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $user = User::factory()->create(['role_id' => $adminRole->id]);

        $this->assertFalse($user->isCustomer());
    }

    #[Test]
    public function has_role_returns_true_for_matching_role()
    {
        $instructorRole = Role::where('name', 'instructor')->first();
        $user = User::factory()->create(['role_id' => $instructorRole->id]);

        $this->assertTrue($user->hasRole('instructor'));
    }

    #[Test]
    public function has_role_returns_false_for_non_matching_role()
    {
        $customerRole = Role::where('name', 'customer')->first();
        $user = User::factory()->create(['role_id' => $customerRole->id]);

        $this->assertFalse($user->hasRole('admin'));
    }

    #[Test]
    public function it_can_get_bookings_as_both_customer_and_instructor()
    {
        $user = User::factory()->create();

        // User makes bookings as a customer
        $customerBookings = Booking::factory()->count(2)->create(['user_id' => $user->id]);

        // User also teaches as an instructor
        $instructorBookings = Booking::factory()->count(3)->create(['instructor_id' => $user->id]);

        $this->assertCount(2, $user->bookings);
        $this->assertCount(3, $user->instructorBookings);
    }

    #[Test]
    public function fillable_attributes_are_correct()
    {
        $user = new User();
        $expectedFillable = [
            'name',
            'email',
            'password',
            'role_id',
            'phone',
            'address',
            'experience_level',
        ];

        $this->assertEquals($expectedFillable, $user->getFillable());
    }

    #[Test]
    public function hidden_attributes_are_correct()
    {
        $user = new User();
        $expectedHidden = [
            'password',
            'remember_token',
        ];

        $this->assertEquals($expectedHidden, $user->getHidden());
    }
}
