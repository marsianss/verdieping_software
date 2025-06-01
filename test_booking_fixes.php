<?php

require_once 'vendor/autoload.php';

use App\Models\Booking;
use Carbon\Carbon;

echo "=== Kitesurf School Booking System Test ===\n\n";

// Test 1: Date parsing (the main issue that was causing problems)
echo "Test 1: Testing date parsing fixes...\n";
try {
    $booking = Booking::first();
    if ($booking) {
        // Test the date parsing that was causing errors
        $startDateTime = Carbon::parse($booking->date->format('Y-m-d') . ' ' . $booking->start_time);
        $endDateTime = Carbon::parse($booking->date->format('Y-m-d') . ' ' . $booking->end_time);

        echo "✅ Date parsing SUCCESS!\n";
        echo "   Booking Date: " . $booking->date->format('Y-m-d') . "\n";
        echo "   Start Time: " . $booking->start_time . "\n";
        echo "   End Time: " . $booking->end_time . "\n";
        echo "   Parsed Start: " . $startDateTime->format('Y-m-d H:i:s') . "\n";
        echo "   Parsed End: " . $endDateTime->format('Y-m-d H:i:s') . "\n";
    } else {
        echo "❌ No bookings found in database\n";
    }
} catch (Exception $e) {
    echo "❌ Date parsing FAILED: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Model casts
echo "Test 2: Testing Booking model casts...\n";
try {
    $booking = new Booking();
    $casts = $booking->getCasts();

    if (isset($casts['date']) && $casts['date'] === 'date') {
        echo "✅ Date cast is correctly set to 'date'\n";
    } else {
        echo "❌ Date cast issue\n";
    }

    if (!isset($casts['start_time']) && !isset($casts['end_time'])) {
        echo "✅ Problematic time casts have been removed\n";
    } else {
        echo "❌ Time casts still present (this could cause issues)\n";
    }
} catch (Exception $e) {
    echo "❌ Model cast test FAILED: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Check booking statuses
echo "Test 3: Testing booking status functionality...\n";
try {
    $statuses = ['pending', 'confirmed', 'cancelled', 'completed'];
    $booking = Booking::first();

    if ($booking) {
        foreach ($statuses as $status) {
            $booking->status = $status;
            $booking->save();
            echo "✅ Status '$status' can be set successfully\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Status test FAILED: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: User relationships
echo "Test 4: Testing User-Booking relationships...\n";
try {
    $user = \App\Models\User::where('role_id', 2)->first(); // Instructor
    if ($user) {
        $bookings = $user->instructorBookings()->count();
        echo "✅ Instructor bookings relationship works: {$bookings} bookings found\n";
    }

    $customer = \App\Models\User::where('role_id', 3)->first(); // Customer
    if ($customer) {
        $bookings = $customer->bookings()->count();
        echo "✅ Customer bookings relationship works: {$bookings} bookings found\n";
    }
} catch (Exception $e) {
    echo "❌ User relationship test FAILED: " . $e->getMessage() . "\n";
}

echo "\n=== Test Summary ===\n";
echo "All core booking system issues have been tested.\n";
echo "If all tests show ✅, the system is ready for sale!\n";
