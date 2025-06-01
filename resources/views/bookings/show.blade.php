<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('bookings.index') }}" class="text-cyan-600 hover:text-cyan-800">
                    &larr; Back to My Bookings
                </a>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Booking #{{ $booking->id }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Created on {{ $booking->created_at->format('F j, Y \a\t g:i a') }}
                            </p>
                        </div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-800' :
                            ($booking->status == 'completed' ? 'bg-blue-100 text-blue-800' :
                            ($booking->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>

                    <div class="mt-6 border-t border-gray-200">
                        <dl class="divide-y divide-gray-200">
                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Lesson</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
                                    {{ $booking->lesson->name }}
                                    <p class="text-sm text-gray-500 mt-1">{{ $booking->lesson->description }}</p>
                                </dd>
                            </div>

                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Date</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
                                    {{ \Carbon\Carbon::parse($booking->date)->format('l, F j, Y') }}
                                </dd>
                            </div>

                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Time</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i a') }} -
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i a') }}
                                    <span class="text-sm text-gray-500 ml-2">({{ \Carbon\Carbon::parse($booking->end_time)->diffInHours(\Carbon\Carbon::parse($booking->start_time)) }} hours)</span>
                                </dd>
                            </div>

                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Instructor</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
                                    {{ $booking->instructor->name }}
                                </dd>
                            </div>

                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Participants</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
                                    {{ $booking->participants }}
                                </dd>
                            </div>

                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Total Price</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
                                    €{{ number_format($booking->total_price, 2) }}
                                    <p class="text-sm text-gray-500 mt-1">
                                        €{{ number_format($booking->lesson->price, 2) }} per participant × {{ $booking->participants }} participants
                                    </p>
                                </dd>
                            </div>

                            @if($booking->notes)
                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Special Requests</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
                                    {{ $booking->notes }}
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>                    <!-- Action Buttons -->
                    <div class="mt-6 flex space-x-3 border-t border-gray-200 pt-6">
                        @if($booking->status === 'pending' || $booking->status === 'confirmed')
                            @if(\Carbon\Carbon::parse($booking->date->format('Y-m-d') . ' ' . $booking->start_time)->isFuture())
                                <a href="{{ route('bookings.edit', $booking) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                    </svg>
                                    Edit Booking
                                </a>
                            @endif

                            @if($booking->status !== 'cancelled' && \Carbon\Carbon::parse($booking->date->format('Y-m-d') . ' ' . $booking->start_time)->isFuture())
                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7 382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Cancel Booking
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                                        Cancel Booking
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>

                    @if($booking->status === 'cancelled')
                        <div class="mt-6 bg-red-50 border-l-4 border-red-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        This booking has been cancelled. Please contact us if you wish to reschedule.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($booking->status === 'confirmed' && \Carbon\Carbon::parse($booking->date->format('Y-m-d') . ' ' . $booking->start_time)->isPast())
                        <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        This booking date has passed. Please contact us if you have any questions about your lesson.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
