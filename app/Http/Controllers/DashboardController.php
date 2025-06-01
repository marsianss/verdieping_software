<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Make sure user is authenticated before trying to access properties
        if (!$user) {
            return redirect()->route('login');
        }

        // Redirect to role-specific dashboards
        if ($user->isInstructor()) {
            return redirect()->route('instructor.dashboard');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // For regular users (customers), show their upcoming bookings
        $upcomingBooking = Booking::where('user_id', $user->id)
                        ->where('date', '>=', now()->format('Y-m-d'))
                        ->where('status', 'confirmed')
                        ->orderBy('date')
                        ->first();

        $recentBookings = Booking::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

        return view('dashboard', [
            'user' => $user,
            'upcomingBooking' => $upcomingBooking,
            'recentBookings' => $recentBookings
        ]);
    }
}
