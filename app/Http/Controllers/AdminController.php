<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lesson;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with statistics.
     */
    public function dashboard()
    {
        // Get statistics for dashboard
        $totalUsers = User::count();
        $totalCustomers = User::whereHas('role', function($query) {
            $query->where('name', 'customer');
        })->count();
        $totalInstructors = User::whereHas('role', function($query) {
            $query->where('name', 'instructor');
        })->count();

        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();
        $completedBookings = Booking::where('status', 'completed')->count();

        $totalRevenue = Booking::where('status', 'confirmed')
            ->orWhere('status', 'completed')
            ->sum('total_price');

        $recentBookings = Booking::with(['user', 'lesson', 'instructor'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalCustomers',
            'totalInstructors',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'cancelledBookings',
            'completedBookings',
            'totalRevenue',
            'recentBookings'
        ));
    }

    /**
     * Display a listing of lessons.
     */
    public function lessons()
    {
        $lessons = Lesson::paginate(10);

        return view('admin.lessons.index', [
            'lessons' => $lessons
        ]);
    }

    /**
     * Show the form for creating a new lesson.
     */
    public function createLesson()
    {
        return view('admin.lessons.create');
    }

    /**
     * Store a newly created lesson in storage.
     */
    public function storeLesson(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:30|max:480',
            'price' => 'required|numeric|min:0',
            'max_participants' => 'required|integer|min:1|max:20',
            'difficulty_level' => 'required|string|in:beginner,intermediate,advanced,any',
            'image_url' => 'nullable|string|max:255',
        ]);

        Lesson::create($validated);

        return redirect()->route('admin.lessons')
            ->with('success', 'Lesson created successfully.');
    }

    /**
     * Show the form for editing the specified lesson.
     */
    public function editLesson(Lesson $lesson)
    {
        return view('admin.lessons.edit', [
            'lesson' => $lesson
        ]);
    }

    /**
     * Update the specified lesson in storage.
     */
    public function updateLesson(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:30|max:480',
            'price' => 'required|numeric|min:0',
            'max_participants' => 'required|integer|min:1|max:20',
            'difficulty_level' => 'required|string|in:beginner,intermediate,advanced,any',
            'image_url' => 'nullable|string|max:255',
        ]);

        $lesson->update($validated);

        return redirect()->route('admin.lessons')
            ->with('success', 'Lesson updated successfully.');
    }

    /**
     * Remove the specified lesson from storage.
     */
    public function destroyLesson(Lesson $lesson)
    {
        // Check if lesson has bookings
        if ($lesson->bookings()->exists()) {
            return redirect()->route('admin.lessons')
                ->with('error', 'Cannot delete lesson with existing bookings.');
        }

        $lesson->delete();

        return redirect()->route('admin.lessons')
            ->with('success', 'Lesson deleted successfully.');
    }

    /**
     * Display a listing of bookings.
     */
    public function bookings()
    {
        $bookings = Booking::with(['user', 'lesson', 'instructor'])
            ->latest()
            ->paginate(15);

        return view('admin.bookings.index', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function editBooking(Booking $booking)
    {
        $lessons = Lesson::all();
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'customer');
        })->get();
        $instructors = User::whereHas('role', function($query) {
            $query->where('name', 'instructor');
        })->get();

        return view('admin.bookings.edit', [
            'booking' => $booking,
            'lessons' => $lessons,
            'customers' => $customers,
            'instructors' => $instructors
        ]);
    }

    /**
     * Update the specified booking in storage.
     */
    public function updateBooking(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'lesson_id' => 'required|exists:lessons,id',
            'instructor_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'participants' => 'required|integer|min:1|max:10',
            'total_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Get the lesson to calculate end time
        $lesson = Lesson::findOrFail($validated['lesson_id']);

        // Calculate end time based on lesson duration
        $startTime = $validated['start_time'];
        $endTime = date('H:i:s', strtotime($startTime . ' + ' . $lesson->duration . ' minutes'));

        // Update the booking
        $booking->update([
            'user_id' => $validated['user_id'],
            'lesson_id' => $validated['lesson_id'],
            'instructor_id' => $validated['instructor_id'],
            'date' => $validated['date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $validated['status'],
            'participants' => $validated['participants'],
            'total_price' => $validated['total_price'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.bookings')
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroyBooking(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings')
            ->with('success', 'Booking deleted successfully.');
    }

    /**
     * Display a listing of users.
     */
    public function users()
    {
        $users = User::with('role')->paginate(15);

        return view('admin.users.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function createUser()
    {
        $roles = Role::all();

        return view('admin.users.create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'experience_level' => 'nullable|string|in:beginner,intermediate,advanced,expert',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'experience_level' => $validated['experience_level'] ?? null,
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function editUser(User $user)
    {
        $roles = Role::all();

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'experience_level' => 'nullable|string|in:beginner,intermediate,advanced,expert',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'experience_level' => $validated['experience_level'] ?? null,
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroyUser(User $user)
    {
        // Check if user has bookings
        if ($user->bookings()->exists() || $user->instructorBookings()->exists()) {
            return redirect()->route('admin.users')
                ->with('error', 'Cannot delete user with existing bookings.');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully.');
    }
}
