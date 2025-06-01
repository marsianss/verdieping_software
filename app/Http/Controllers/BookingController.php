<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    /**
     * Display a listing of the user's bookings.
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()->with(['lesson', 'instructor'])->paginate(10);

        return view('bookings.index', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Request $request)
    {
        $lessons = Lesson::all();
        $instructors = User::whereHas('role', function($query) {
            $query->where('name', 'instructor');
        })->get();

        // Get the selected lesson if it's provided in the request
        $lesson = null;
        if ($request->has('lesson')) {
            $lesson = Lesson::find($request->lesson);
        }

        return view('bookings.create', [
            'lessons' => $lessons,
            'instructors' => $instructors,
            'lesson' => $lesson
        ]);
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'instructor_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'participants' => 'required|integer|min:1|max:10',
            'participant_name' => 'required|array|size:'.$request->participants,
            'participant_name.*' => 'required|string|max:255',
            'participant_age' => 'required|array|size:'.$request->participants,
            'participant_age.*' => 'required|integer|min:8|max:99',
            'participant_experience' => 'nullable|array',
            'participant_experience.*' => 'nullable|boolean',
            'notes' => 'nullable|string',
            'terms' => 'required|accepted',
        ]);

        // Get the lesson to calculate end time and total price
        $lesson = Lesson::findOrFail($validated['lesson_id']);

        // Calculate end time based on lesson duration
        $startTime = $validated['time'];
        $endTime = date('H:i:s', strtotime($startTime . ' + ' . $lesson->duration . ' minutes'));

        // Calculate total price based on number of participants
        $totalPrice = $lesson->price * $validated['participants'];

        // Create the booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'lesson_id' => $validated['lesson_id'],
            'instructor_id' => $validated['instructor_id'],
            'date' => $validated['date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
            'participants' => $validated['participants'],
            'total_price' => $totalPrice,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Send confirmation email (in a real app)
        // Mail::to(Auth::user()->email)->send(new BookingConfirmation($booking));

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Your booking has been created and is awaiting confirmation.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        // Authorize that the user can view this booking
        $this->authorize('view', $booking);

        return view('bookings.show', [
            'booking' => $booking->load(['lesson', 'instructor'])
        ]);
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking)
    {
        // Authorize that the user can edit this booking
        $this->authorize('update', $booking);

        $lessons = Lesson::all();
        $instructors = User::whereHas('role', function($query) {
            $query->where('name', 'instructor');
        })->get();

        return view('bookings.edit', [
            'booking' => $booking,
            'lessons' => $lessons,
            'instructors' => $instructors
        ]);
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        // Authorize that the user can update this booking
        $this->authorize('update', $booking);

        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'instructor_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'participants' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string',
        ]);

        // Get the lesson to calculate end time and total price
        $lesson = Lesson::findOrFail($validated['lesson_id']);

        // Calculate end time based on lesson duration
        $startTime = $validated['start_time'];
        $endTime = date('H:i:s', strtotime($startTime . ' + ' . $lesson->duration . ' minutes'));

        // Calculate total price based on number of participants
        $totalPrice = $lesson->price * $validated['participants'];

        // Update the booking
        $booking->update([
            'lesson_id' => $validated['lesson_id'],
            'instructor_id' => $validated['instructor_id'],
            'date' => $validated['date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending', // Reset to pending when customer makes changes
            'participants' => $validated['participants'],
            'total_price' => $totalPrice,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Your booking has been updated and is awaiting confirmation.');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Booking $booking)
    {
        // Authorize that the user can delete this booking
        $this->authorize('delete', $booking);

        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Your booking has been cancelled.');
    }
}
