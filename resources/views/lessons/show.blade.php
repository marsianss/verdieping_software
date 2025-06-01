<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $lesson->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Lesson Image -->
                        <div class="lg:col-span-1 rounded-lg h-80 overflow-hidden">
                            <img src="{{ $lesson->image_url }}" alt="{{ $lesson->name }}" class="w-full h-full object-cover">
                        </div>

                        <!-- Lesson Details -->
                        <div class="lg:col-span-2">
                            <div class="flex flex-wrap items-center mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800 mr-3">
                                    {{ ucfirst($lesson->difficulty_level) }}
                                </span>
                                <span class="text-gray-600 flex items-center mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $lesson->duration }} minutes
                                </span>
                                <span class="text-2xl font-bold text-cyan-700">
                                    €{{ number_format($lesson->price, 2) }}
                                </span>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                            <div class="prose max-w-none mb-6">
                                <p>{{ $lesson->description }}</p>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 mb-2">What You'll Learn</h3>
                            <ul class="list-disc pl-5 mb-6 space-y-1 text-gray-600">
                                @if($lesson->difficulty_level == 'beginner')
                                    <li>Basic kitesurfing safety and equipment knowledge</li>
                                    <li>Kite control techniques in various wind conditions</li>
                                    <li>Body dragging and water starts</li>
                                    <li>Basic riding and stopping techniques</li>
                                @elseif($lesson->difficulty_level == 'intermediate')
                                    <li>Advanced board control and edging techniques</li>
                                    <li>Transitioning between directions (tacking)</li>
                                    <li>Improved water starts and stance control</li>
                                    <li>Introduction to basic jumps and tricks</li>
                                @else
                                    <li>Advanced jump techniques and rotations</li>
                                    <li>Riding in challenging wind conditions</li>
                                    <li>Wave riding techniques</li>
                                    <li>Freestyle tricks and performance riding</li>
                                @endif
                            </ul>

                            <h3 class="text-lg font-semibold text-gray-900 mb-2">What's Included</h3>
                            <ul class="list-disc pl-5 mb-6 space-y-1 text-gray-600">
                                <li>Professional instruction from certified kitesurfing instructors</li>
                                <li>All necessary equipment (kite, board, harness, wetsuit, helmet)</li>
                                <li>Safety training and instruction</li>
                                <li>Insurance during your lesson</li>
                            </ul>

                            <div class="mt-6">
                                @auth
                                    <a href="{{ route('bookings.create', ['lesson' => $lesson->id]) }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                                        Book This Lesson
                                    </a>
                                @else
                                    <div class="rounded-md bg-yellow-50 p-4 mb-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800">Please login to book a lesson</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 border border-cyan-600 text-base font-medium rounded-md text-cyan-600 bg-white hover:bg-cyan-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                                        Login to Book
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Frequently Asked Questions</h3>

                    <div class="space-y-4">
                        <div>
                            <h4 class="text-lg font-medium text-cyan-800">What should I bring to my lesson?</h4>
                            <p class="mt-1 text-gray-600">Bring a swimsuit, towel, sunscreen, and a positive attitude! We provide all the kitesurfing equipment needed for your lesson.</p>
                        </div>

                        <div>
                            <h4 class="text-lg font-medium text-cyan-800">What if the weather is not suitable for kitesurfing?</h4>
                            <p class="mt-1 text-gray-600">If weather conditions are unsuitable for kitesurfing (insufficient wind, storms, etc.), we will reschedule your lesson at no additional cost.</p>
                        </div>

                        <div>
                            <h4 class="text-lg font-medium text-cyan-800">Do I need to know how to swim?</h4>
                            <p class="mt-1 text-gray-600">Yes, basic swimming ability is required for safety reasons. All participants must be comfortable in water and able to swim at least 50 meters.</p>
                        </div>

                        <div>
                            <h4 class="text-lg font-medium text-cyan-800">Is there an age limit for lessons?</h4>
                            <p class="mt-1 text-gray-600">Our standard lessons are for participants aged 12 and above. For younger children (ages 8-11), we offer special kids' programs with smaller kites and additional safety measures.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Lessons -->
            @if($relatedLessons->count() > 0)
            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">You Might Also Like</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedLessons as $relatedLesson)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900">{{ $relatedLesson->name }}</h4>
                            <div class="flex items-center text-sm text-gray-500 mt-1 mb-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800 mr-2">
                                    {{ ucfirst($relatedLesson->difficulty_level) }}
                                </span>
                                <span>€{{ number_format($relatedLesson->price, 2) }}</span>
                            </div>
                            <a href="{{ route('lessons.show', $relatedLesson) }}" class="text-sm text-cyan-600 hover:text-cyan-900">
                                View Details →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
