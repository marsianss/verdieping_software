<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Confirmation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">Booking Confirmed!</h3>
                        <p class="text-gray-600">Your kitesurfing lesson has been successfully booked.</p>
                    </div>

                    <div class="border-t border-b border-gray-200 py-6 mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Booking Details</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h5 class="text-sm font-medium text-gray-500 mb-1">Booking Reference</h5>
                                <p class="text-gray-900 font-medium">{{ $booking->reference_number }}</p>
                            </div>
                            
                            <div>
                                <h5 class="text-sm font-medium text-gray-500 mb-1">Status</h5>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Confirmed
                                </span>
                            </div>
                            
                            <div>
                                <h5 class="text-sm font-medium text-gray-500 mb-1">Lesson</h5>
                                <p class="text-gray-900">{{ $booking->lesson->name }}</p>
                            </div>
                            
                            <div>
                                <h5 class="text-sm font-medium text-gray-500 mb-1">Date & Time</h5>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($booking->date)->format('l, F j, Y') }} at {{ \Carbon\Carbon::parse($booking->time)->format('g:i A') }}</p>
                            </div>
                            
                            <div>
                                <h5 class="text-sm font-medium text-gray-500 mb-1">Participants</h5>
                                <p class="text-gray-900">{{ $booking->participants }}</p>
                            </div>
                            
                            <div>
                                <h5 class="text-sm font-medium text-gray-500 mb-1">Total Price</h5>
                                <p class="text-gray-900 font-medium">€{{ number_format($booking->total_price, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">What's Next</h4>
                        
                        <div class="rounded-md bg-cyan-50 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-cyan-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-cyan-800">Important Information</h3>
                                    <div class="mt-2 text-sm text-cyan-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>A confirmation email has been sent to your email address</li>
                                            <li>Please arrive 30 minutes before your lesson time</li>
                                            <li>Bring a swimsuit, towel, and sunscreen</li>
                                            <li>All equipment will be provided</li>
                                            <li>Our instructors will meet you at the Windkracht-12 beach hut</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-base font-medium text-gray-900 mb-2">Location</h5>
                            <p class="text-gray-600 mb-1">Windkracht-12 Kitesurfing School</p>
                            <p class="text-gray-600 mb-1">Strandweg 12</p>
                            <p class="text-gray-600 mb-1">1234 AB Zandvoort</p>
                            <p class="text-gray-600">The Netherlands</p>
                        </div>
                        
                        <div>
                            <h5 class="text-base font-medium text-gray-900 mb-2">Weather Policy</h5>
                            <p class="text-gray-600 mb-3">We monitor weather conditions closely. If conditions are unsuitable for kitesurfing on your booked date, we will contact you 24 hours in advance to reschedule your lesson.</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-between">
                        <a href="{{ route('dashboard') }}" class="mb-3 sm:mb-0 inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Back to Dashboard
                        </a>
                        <a href="{{ route('bookings.show', $booking) }}" class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                            View Booking Details
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>