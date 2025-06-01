@extends('layouts.admin')

@section('content')
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Admin Dashboard</h1>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Users Card -->
            <div class="bg-blue-500 text-white rounded-lg p-4 shadow">
                <div class="flex items-center">
                    <div class="mr-4">
                        <i class="fas fa-users text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Total Users</h3>
                        <p class="text-2xl font-bold">{{ $totalUsers }}</p>
                        <div class="text-sm">
                            <span>{{ $totalCustomers }} Customers</span> |
                            <span>{{ $totalInstructors }} Instructors</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Bookings Card -->
            <div class="bg-green-500 text-white rounded-lg p-4 shadow">
                <div class="flex items-center">
                    <div class="mr-4">
                        <i class="fas fa-calendar-alt text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Total Bookings</h3>
                        <p class="text-2xl font-bold">{{ $totalBookings }}</p>
                        <div class="text-sm">
                            <span>{{ $pendingBookings }} Pending</span> |
                            <span>{{ $confirmedBookings }} Confirmed</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Revenue Card -->
            <div class="bg-purple-500 text-white rounded-lg p-4 shadow">
                <div class="flex items-center">
                    <div class="mr-4">
                        <i class="fas fa-euro-sign text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Total Revenue</h3>
                        <p class="text-2xl font-bold">€{{ number_format($totalRevenue, 2) }}</p>
                        <div class="text-sm">
                            From confirmed & completed bookings
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Booking Status Card -->
            <div class="bg-orange-500 text-white rounded-lg p-4 shadow">
                <div class="flex items-center">
                    <div class="mr-4">
                        <i class="fas fa-chart-pie text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Booking Status</h3>
                        <div class="text-sm">
                            <p>Confirmed: {{ $confirmedBookings }}</p>
                            <p>Pending: {{ $pendingBookings }}</p>
                            <p>Completed: {{ $completedBookings }}</p>
                            <p>Cancelled: {{ $cancelledBookings }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Bookings -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Bookings</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left">Customer</th>
                            <th class="py-3 px-4 text-left">Lesson</th>
                            <th class="py-3 px-4 text-left">Date</th>
                            <th class="py-3 px-4 text-left">Instructor</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Price</th>
                            <th class="py-3 px-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentBookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $booking->id }}</td>
                            <td class="py-3 px-4">{{ $booking->user->name }}</td>
                            <td class="py-3 px-4">{{ $booking->lesson->name }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($booking->date)->format('d-m-Y') }} {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                            <td class="py-3 px-4">{{ $booking->instructor->name }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                    @elseif($booking->status == 'completed') bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">€{{ $booking->total_price }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.bookings.edit', $booking) }}" class="text-blue-500 hover:underline">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 text-right">
                <a href="{{ route('admin.bookings') }}" class="text-blue-500 hover:underline">View All Bookings</a>
            </div>
        </div>
    </div>
@endsection