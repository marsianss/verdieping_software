<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">{{ __("Welcome back") }}{{ $user ? ', '.$user->name : '' }}!</h3>
                    <p class="mt-1 text-sm text-gray-600">{{ __("Here's an overview of your account.") }}</p>
                </div>
            </div>

            @if(isset($upcomingBooking))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">{{ __("Your Next Lesson") }}</h3>
                    <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                        <div class="flex flex-col md:flex-row justify-between">
                            <div>
                                <h4 class="font-semibold text-lg text-cyan-800">{{ $upcomingBooking->lesson->name ?? 'Kitesurfing Lesson' }}</h4>
                                <p class="text-cyan-600">
                                    <span class="font-medium">Date:</span> {{ isset($upcomingBooking->date) ? $upcomingBooking->date->format('d-m-Y') : 'N/A' }}
                                </p>
                                <p class="text-cyan-600">
                                    <span class="font-medium">Time:</span> {{ $upcomingBooking->time_slot ?? 'N/A' }}
                                </p>
                                <div class="mt-2">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Confirmed
                                    </span>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <a href="{{ route('bookings.show', $upcomingBooking) }}" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition ease-in-out duration-150">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">{{ __("Your Recent Bookings") }}</h3>

                    @if(isset($recentBookings) && $recentBookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lesson</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentBookings as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $booking->lesson->name ?? 'Lesson not found' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ isset($booking->date) ? $booking->date->format('d-m-Y') : 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $booking->time_slot ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                                    @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($booking->status ?? 'unknown') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('bookings.show', $booking) }}" class="text-cyan-600 hover:text-cyan-900 mr-3">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-right">
                            <a href="{{ route('bookings.index') }}" class="text-sm text-cyan-600 hover:text-cyan-900">View all bookings →</a>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <p class="text-gray-600 mb-4">You haven't made any bookings yet.</p>
                            <a href="{{ route('lessons.index') }}" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 focus:bg-cyan-700 active:bg-cyan-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition ease-in-out duration-150">
                                Book a Lesson Now
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
