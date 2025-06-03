@extends('layouts.admin')

@section('content')
    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-r from-blue-700 to-blue-600 p-6 text-white">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Manage Bookings</h1>
                <div class="flex gap-3">
                    <div class="text-white/80 text-sm">
                        <i class="fas fa-info-circle mr-1"></i>
                        View and manage all customer bookings
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="py-3 px-4 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Customer</th>
                        <th class="py-3 px-4 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Lesson</th>
                        <th class="py-3 px-4 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="py-3 px-4 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Time</th>
                        <th class="py-3 px-4 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Instructor</th>
                        <th class="py-3 px-4 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-4 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Price</th>
                        <th class="py-3 px-4 text-left font-semibold text-sm text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="py-4 px-4 text-gray-800">{{ $booking->user->name }}</td>
                        <td class="py-4 px-4 text-gray-800">{{ $booking->lesson->name }}</td>
                        <td class="py-4 px-4 text-gray-800">{{ \Carbon\Carbon::parse($booking->date)->format('d-m-Y') }}</td>
                        <td class="py-4 px-4 text-gray-800">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                        <td class="py-4 px-4 text-gray-800">{{ $booking->instructor->name }}</td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                @elseif($booking->status == 'completed') bg-blue-100 text-blue-800
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-gray-800 font-semibold">€{{ $booking->total_price }}</td>
                        <td class="py-4 px-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.bookings.edit', $booking) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                @if($booking->status === 'cancelled')
                                    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm flex items-center">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="bg-gray-400 cursor-not-allowed text-white px-3 py-1 rounded-md text-sm flex items-center opacity-50" title="Can only delete cancelled bookings">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t">
            {{ $bookings->links() }}
        </div>

        <div class="bg-gray-50 px-6 py-3 text-right border-t">
            <p class="text-sm text-gray-500">Total bookings: <span class="font-semibold">{{ $bookings->total() }}</span></p>
        </div>
    </div>

    <style>
        /* Custom pagination styling */
        nav[role=navigation] span.relative,
        nav[role=navigation] a.relative {
            @apply inline-flex items-center px-4 py-2 text-sm font-medium rounded-md;
        }

        nav[role=navigation] span.bg-white {
            @apply bg-blue-50 text-blue-600 border-blue-300;
        }

        nav[role=navigation] a.bg-white {
            @apply text-gray-700 bg-white border-gray-300 hover:bg-gray-50;
        }
    </style>
@endsection
