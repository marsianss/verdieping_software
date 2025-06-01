<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorController extends Controller
{
    /**
     * Display the instructor dashboard.
     */
    public function dashboard()
    {
        // Get upcoming bookings for this instructor
        $upcomingBookings = Auth::user()->instructorBookings()
            ->with(['user', 'lesson'])
            ->where('date', '>=', now()->format('Y-m-d'))
            ->where('status', 'confirmed')
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        // Get statistics
        $totalBookings = Auth::user()->instructorBookings()->count();
        $pendingBookings = Auth::user()->instructorBookings()->where('status', 'pending')->count();
        $todayBookings = Auth::user()->instructorBookings()
            ->where('date', now()->format('Y-m-d'))
            ->count();

        return view('instructor.dashboard', [
            'upcomingBookings' => $upcomingBookings,
            'totalBookings' => $totalBookings,
            'pendingBookings' => $pendingBookings,
            'todayBookings' => $todayBookings
        ]);
    }

    /**
     * Display the instructor's schedule.
     */
    public function schedule()
    {
        // Get all confirmed bookings for this instructor for the next 30 days
        $bookings = Auth::user()->instructorBookings()
            ->with(['user', 'lesson'])
            ->where('date', '>=', now()->format('Y-m-d'))
            ->where('date', '<=', now()->addDays(30)->format('Y-m-d'))
            ->where('status', 'confirmed')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('date');

        return view('instructor.schedule', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Display all bookings for this instructor.
     */
    public function bookings()
    {
        $bookings = Auth::user()->instructorBookings()
            ->with(['user', 'lesson'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time')
            ->paginate(15);

        return view('instructor.bookings.index', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Update the status of a booking (confirm/cancel).
     */
    public function updateBookingStatus(Request $request, Booking $booking)
    {
        // Ensure the instructor is assigned to this booking
        if ($booking->instructor_id !== Auth::id()) {
            abort(403, 'You are not authorized to update this booking.');
        }

        $validated = $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed',
        ]);

        $booking->update([
            'status' => $validated['status']
        ]);

        // Send email notification to customer (in a real app)
        // Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking));

        return redirect()->route('instructor.bookings')
            ->with('success', 'Booking status has been updated to ' . $validated['status']);
    }
}
