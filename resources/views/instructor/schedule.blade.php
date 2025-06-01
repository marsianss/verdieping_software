<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <a href="{{ route('instructor.dashboard') }}" class="text-cyan-600 hover:text-cyan-800">
                        &larr; Back to Dashboard
                    </a>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('instructor.bookings') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Manage Bookings
                    </a>
                </div>
            </div>

            <!-- Schedule Calendar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Your 30-Day Schedule</h3>
                    
                    @if(count($bookings) > 0)
                        <div class="space-y-6">
                            @foreach($bookings as $date => $dateBookings)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-md font-medium text-gray-900 mb-3">
                                        {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                        @if(\Carbon\Carbon::parse($date)->isToday())
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Today
                                            </span>
                                        @endif
                                    </h4>
                                    
                                    <div class="space-y-3">
                                        @foreach($dateBookings->sortBy('start_time') as $booking)
                                            <div class="bg-white p-3 rounded border {{ $booking->status == 'confirmed' ? 'border-green-200' : 'border-gray-200' }}">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <div class="text-sm font-semibold text-gray-900">
                                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                                        </div>
                                                        <div class="mt-1 text-sm text-gray-700">
                                                            <span class="font-medium">{{ $booking->lesson->name }}</span> 
                                                            <span class="text-gray-500">with {{ $booking->participants }} participant(s)</span>
                                                        </div>
                                                        <div class="mt-1 text-sm text-gray-700">
                                                            Customer: {{ $booking->user->name }} 
                                                            <a href="mailto:{{ $booking->user->email }}" class="text-cyan-600 hover:text-cyan-800">
                                                                {{ $booking->user->email }}
                                                            </a>
                                                        </div>
                                                        @if($booking->notes)
                                                            <div class="mt-2 text-sm text-gray-600">
                                                                <span class="font-medium">Notes:</span> {{ $booking->notes }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                        ($booking->status == 'completed' ? 'bg-blue-100 text-blue-800' : 
                                                        ($booking->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </div>
                                                
                                                @if($booking->status == 'confirmed' && \Carbon\Carbon::parse($booking->date)->isPast())
                                                    <div class="mt-3 text-right">
                                                        <form method="POST" action="{{ route('instructor.bookings.status', $booking) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                                Mark as Completed
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 p-4 rounded-md">
                            <p class="text-gray-700">No scheduled bookings found for the next 30 days.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>