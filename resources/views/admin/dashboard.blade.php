@extends('layouts.admin')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard Overview</h1>
                    <p class="text-gray-600 mt-1">Welcome back! Here's what's happening at your kitesurf school.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ now()->format('M d, Y') }}
                    </div>
                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                    <span class="text-sm text-green-600 font-medium">System Operational</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-2">
                        <i class="fas fa-users text-2xl mr-3 opacity-80"></i>
                        <h3 class="text-lg font-semibold">Total Users</h3>
                    </div>
                    <p class="text-3xl font-bold mb-1">{{ $totalUsers }}</p>
                    <div class="text-sm opacity-80">
                        <span>{{ $totalCustomers }} Customers</span> •
                        <span>{{ $totalInstructors }} Instructors</span>
                    </div>
                </div>
                <div class="text-right opacity-20">
                    <i class="fas fa-users text-5xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Bookings Card -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-2">
                        <i class="fas fa-calendar-alt text-2xl mr-3 opacity-80"></i>
                        <h3 class="text-lg font-semibold">Bookings</h3>
                    </div>
                    <p class="text-3xl font-bold mb-1">{{ $totalBookings }}</p>
                    <div class="text-sm opacity-80">
                        <span>{{ $pendingBookings }} Pending</span> •
                        <span>{{ $confirmedBookings }} Confirmed</span>
                    </div>
                </div>
                <div class="text-right opacity-20">
                    <i class="fas fa-calendar-alt text-5xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-2">
                        <i class="fas fa-euro-sign text-2xl mr-3 opacity-80"></i>
                        <h3 class="text-lg font-semibold">Revenue</h3>
                    </div>
                    <p class="text-3xl font-bold mb-1">€{{ number_format($totalRevenue, 0) }}</p>
                    <div class="text-sm opacity-80">
                        From confirmed bookings
                    </div>
                </div>
                <div class="text-right opacity-20">
                    <i class="fas fa-chart-line text-5xl"></i>
                </div>
            </div>
        </div>

        <!-- Booking Status Card -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl p-6 shadow-lg transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-2">
                        <i class="fas fa-chart-pie text-2xl mr-3 opacity-80"></i>
                        <h3 class="text-lg font-semibold">Status</h3>
                    </div>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span>Confirmed:</span>
                            <span class="font-semibold">{{ $confirmedBookings }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pending:</span>
                            <span class="font-semibold">{{ $pendingBookings }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Completed:</span>
                            <span class="font-semibold">{{ $completedBookings }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-right opacity-20">
                    <i class="fas fa-chart-pie text-5xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-clock mr-3 text-blue-600"></i>
                    Recent Bookings
                </h2>
                <a href="{{ route('admin.bookings') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    <span>View All</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-6 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Customer</th>
                        <th class="py-3 px-6 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Lesson</th>
                        <th class="py-3 px-6 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Date & Time</th>
                        <th class="py-3 px-6 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Instructor</th>
                        <th class="py-3 px-6 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-6 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Price</th>
                        <th class="py-3 px-6 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentBookings as $booking)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="py-4 px-6 text-gray-800">{{ $booking->user->name }}</td>
                        <td class="py-4 px-6 text-gray-800">{{ $booking->lesson->name }}</td>
                        <td class="py-4 px-6 text-gray-800">
                            <div class="text-sm">
                                <div class="font-medium">{{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }}</div>
                                <div class="text-gray-500">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-gray-800">{{ $booking->instructor->name }}</td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                @elseif($booking->status == 'completed') bg-blue-100 text-blue-800
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-gray-800 font-semibold">€{{ $booking->total_price }}</td>
                        <td class="py-4 px-6">
                            <a href="{{ route('admin.bookings.edit', $booking) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm transition-colors duration-200 flex items-center inline-flex">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($recentBookings->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-calendar-alt text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No recent bookings</h3>
            <p class="text-gray-500">Bookings will appear here when customers make new reservations.</p>
        </div>
        @endif
    </div>
@endsection
