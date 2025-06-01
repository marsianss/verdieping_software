<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $customer;
    protected $instructor;

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
    }

    /** @test */
    public function admin_can_view_users_list()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.users'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertSee($this->customer->name);
        $response->assertSee($this->instructor->name);
    }

    /** @test */
    public function admin_can_view_edit_user_form()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.edit', $this->customer));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');
        $response->assertSee($this->customer->name);
        $response->assertSee($this->customer->email);
    }

    /** @test */
    public function admin_can_update_user_basic_information()
    {
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role_id' => $this->customer->role_id,
            'phone' => '+31 6 12345678',
            'address' => '123 Updated Street, Amsterdam',
            'experience_level' => 'intermediate',
        ];

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.users.update', $this->customer), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->customer->refresh();
        $this->assertEquals('Updated Name', $this->customer->name);
        $this->assertEquals('updated@example.com', $this->customer->email);
        $this->assertEquals('+31 6 12345678', $this->customer->phone);
        $this->assertEquals('123 Updated Street, Amsterdam', $this->customer->address);
        $this->assertEquals('intermediate', $this->customer->experience_level);
    }

    /** @test */
    public function admin_can_update_user_password()
    {
        $updateData = [
            'name' => $this->customer->name,
            'email' => $this->customer->email,
            'role_id' => $this->customer->role_id,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.users.update', $this->customer), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->customer->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->customer->password));
    }

    /** @test */
    public function admin_can_update_user_without_changing_password()
    {
        $originalPassword = $this->customer->password;

        $updateData = [
            'name' => 'Updated Name Without Password',
            'email' => $this->customer->email,
            'role_id' => $this->customer->role_id,
            // No password fields - should keep existing password
        ];

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.users.update', $this->customer), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->customer->refresh();
        $this->assertEquals('Updated Name Without Password', $this->customer->name);
        $this->assertEquals($originalPassword, $this->customer->password); // Password should remain unchanged
    }

    /** @test */
    public function admin_can_change_user_role()
    {
        $instructorRole = Role::where('name', 'instructor')->first();

        $updateData = [
            'name' => $this->customer->name,
            'email' => $this->customer->email,
            'role_id' => $instructorRole->id,
        ];

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.users.update', $this->customer), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->customer->refresh();
        $this->assertEquals($instructorRole->id, $this->customer->role_id);
        $this->assertTrue($this->customer->isInstructor());
    }

    /** @test */
    public function admin_user_update_validates_required_fields()
    {
        $invalidData = [
            'name' => '', // Required
            'email' => '', // Required
            'role_id' => '', // Required
        ];

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.users.update', $this->customer), $invalidData);

        $response->assertSessionHasErrors(['name', 'email', 'role_id']);
    }

    /** @test */
    public function admin_user_update_validates_email_format()
    {
        $invalidData = [
            'name' => $this->customer->name,
            'email' => 'invalid-email-format',
            'role_id' => $this->customer->role_id,
        ];

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.users.update', $this->customer), $invalidData);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function admin_user_update_validates_unique_email()
    {
        $otherUser = User::factory()->create();

        $invalidData = [
            'name' => $this->customer->name,
            'email' => $otherUser->email, // Email already exists
            'role_id' => $this->customer->role_id,
        ];

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.users.update', $this->customer), $invalidData);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function admin_user_update_validates_password_confirmation()
    {
        $invalidData = [
            'name' => $this->customer->name,
            'email' => $this->customer->email,
            'role_id' => $this->customer->role_id,
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword', // Doesn't match
        ];

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.users.update', $this->customer), $invalidData);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function admin_user_update_validates_password_minimum_length()
    {
        $invalidData = [
            'name' => $this->customer->name,
            'email' => $this->customer->email,
            'role_id' => $this->customer->role_id,
            'password' => '123', // Too short
            'password_confirmation' => '123',
        ];

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.users.update', $this->customer), $invalidData);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function admin_can_create_new_user()
    {
        $customerRole = Role::where('name', 'customer')->first();

        $userData = [
            'name' => 'New Test User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $customerRole->id,
            'phone' => '+31 6 87654321',
            'address' => '456 New Street, Rotterdam',
            'experience_level' => 'beginner',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), $userData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'New Test User',
            'email' => 'newuser@example.com',
            'role_id' => $customerRole->id,
            'phone' => '+31 6 87654321',
            'address' => '456 New Street, Rotterdam',
            'experience_level' => 'beginner',
        ]);

        $newUser = User::where('email', 'newuser@example.com')->first();
        $this->assertTrue(Hash::check('password123', $newUser->password));
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $userToDelete));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }

    /** @test */
    public function non_admin_cannot_access_user_management()
    {
        $response = $this->actingAs($this->customer)
            ->get(route('admin.users'));

        $response->assertStatus(403); // Forbidden
    }

    /** @test */
    public function non_admin_cannot_update_users()
    {
        $updateData = [
            'name' => 'Unauthorized Update',
            'email' => $this->customer->email,
            'role_id' => $this->customer->role_id,
        ];

        $response = $this->actingAs($this->customer)
            ->patch(route('admin.users.update', $this->customer), $updateData);

        $response->assertStatus(403); // Forbidden
    }

    /** @test */
    public function guest_cannot_access_user_management()
    {
        $response = $this->get(route('admin.users'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function admin_edit_form_shows_all_roles()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.edit', $this->customer));

        $response->assertStatus(200);
        $response->assertSee('admin');
        $response->assertSee('instructor');
        $response->assertSee('customer');
    }

    /** @test */
    public function admin_edit_form_shows_experience_levels()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.edit', $this->customer));

        $response->assertStatus(200);
        $response->assertSee('beginner');
        $response->assertSee('intermediate');
        $response->assertSee('advanced');
        $response->assertSee('expert');
    }

    /** @test */
    public function admin_edit_form_preserves_user_data()
    {
        $user = User::factory()->create([
            'name' => 'Test User Name',
            'email' => 'test@example.com',
            'phone' => '+31 6 12345678',
            'address' => 'Test Address 123',
            'experience_level' => 'advanced',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.users.edit', $user));

        $response->assertStatus(200);
        $response->assertSee('Test User Name');
        $response->assertSee('test@example.com');
        $response->assertSee('+31 6 12345678');
        $response->assertSee('Test Address 123');
        $response->assertSee('selected', false); // Check that advanced is selected
    }
}
