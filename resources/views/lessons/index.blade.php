<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Our Kitesurfing Lessons') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Lesson filters -->
            <div class="mb-8">
                <form action="{{ route('lessons.index') }}" method="GET" class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Difficulty Level</label>
                            <select name="difficulty" id="difficulty" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                                <option value="">All Levels</option>
                                <option value="beginner" {{ request('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ request('difficulty') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ request('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700 mb-1">Min Price (€)</label>
                            <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" min="0" step="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                        </div>
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">Max Price (€)</label>
                            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" min="0" step="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                                Filter Lessons
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Lessons grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($lessons as $lesson)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <img src="{{ $lesson->difficulty_level === 'advanced' ? '/images/lessons/advanced.jpg' : $lesson->image_url }}" alt="{{ $lesson->name }}" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $lesson->name }}</h3>

                            <div class="flex flex-wrap items-center text-sm text-gray-600 mb-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800 mr-2">
                                    {{ ucfirst($lesson->difficulty_level) }}
                                </span>
                                <span class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $lesson->duration }} minutes
                                </span>
                                <span class="font-semibold text-cyan-700">
                                    €{{ number_format($lesson->price, 2) }}
                                </span>
                            </div>

                            <p class="text-gray-600 mb-4">{{ Str::limit($lesson->description, 150) }}</p>

                            <div class="flex justify-between items-center">
                                <a href="{{ route('lessons.show', $lesson) }}" class="text-cyan-600 hover:text-cyan-900">
                                    View Details
                                </a>
                                @auth
                                    <a href="{{ route('bookings.create', ['lesson' => $lesson->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                                        Book Now
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-cyan-600 text-sm font-medium rounded-md text-cyan-600 bg-white hover:bg-cyan-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                                        Login to Book
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <p class="text-gray-500">No lessons found. Please try different filter criteria.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $lessons->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
