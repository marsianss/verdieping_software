<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book a Lesson') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Lesson Summary -->
                        <div class="lg:col-span-1 bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Lesson Summary</h3>

                            @if(isset($lesson))
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-800">{{ $lesson->name }}</h4>
                                    <div class="mt-1 flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800 mr-2">
                                            {{ ucfirst($lesson->difficulty_level) }}
                                        </span>
                                        <span class="text-gray-600 text-sm">{{ $lesson->duration }} minutes</span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm text-gray-600">{{ Str::limit($lesson->description, 150) }}</p>
                                </div>

                                <div class="border-t border-gray-200 pt-4 mb-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Lesson Price:</span>
                                        <span class="font-semibold text-gray-800">€{{ number_format($lesson->price, 2) }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('lessons.show', $lesson) }}" class="text-sm text-cyan-600 hover:text-cyan-900">
                                    « Back to Lesson Details
                                </a>
                            @else
                                <div class="mb-4">
                                    <p class="text-gray-600">Please select a lesson from the dropdown below to continue.</p>
                                </div>

                                <a href="{{ route('lessons.index') }}" class="text-sm text-cyan-600 hover:text-cyan-900">
                                    « Browse Available Lessons
                                </a>
                            @endif
                        </div>

                        <!-- Booking Form -->
                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Book Your Kitesurfing Lesson</h3>

                            <form method="POST" action="{{ route('bookings.store') }}">
                                @csrf
                                @if(isset($lesson))
                                    <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">

                                    <div class="mb-6">
                                        <label for="instructor_id" class="block text-sm font-medium text-gray-700">Select an Instructor</label>
                                        <select name="instructor_id" id="instructor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                            <option value="">-- Select an Instructor --</option>
                                            @foreach($instructors as $instructor)
                                                <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                                    {{ $instructor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('instructor_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @else
                                    <div class="mb-6">
                                        <label for="lesson_id" class="block text-sm font-medium text-gray-700">Select a Lesson</label>
                                        <select name="lesson_id" id="lesson_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                            <option value="">-- Select a Lesson --</option>
                                            @foreach($lessons as $lessonOption)
                                                <option value="{{ $lessonOption->id }}" {{ old('lesson_id') == $lessonOption->id ? 'selected' : '' }}>
                                                    {{ $lessonOption->name }} ({{ ucfirst($lessonOption->difficulty_level) }}) - €{{ number_format($lessonOption->price, 2) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('lesson_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-6">
                                        <label for="instructor_id" class="block text-sm font-medium text-gray-700">Select an Instructor</label>
                                        <select name="instructor_id" id="instructor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                            <option value="">-- Select an Instructor --</option>
                                            @foreach($instructors as $instructor)
                                                <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                                    {{ $instructor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('instructor_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif

                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                        <input type="date" name="date" id="date"
                                            min="{{ date('Y-m-d') }}"
                                            max="{{ date('Y-m-d', strtotime('+3 months')) }}"
                                            value="{{ old('date', date('Y-m-d', strtotime('+1 day'))) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                        @error('date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
                                        <select name="time" id="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                            <option value="09:00" {{ old('time') == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                            <option value="11:00" {{ old('time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                            <option value="13:00" {{ old('time') == '13:00' ? 'selected' : '' }}>1:00 PM</option>
                                            <option value="15:00" {{ old('time') == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                                        </select>
                                        @error('time')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-2">
                                        <label for="participants" class="block text-sm font-medium text-gray-700">Number of Participants</label>
                                        <select name="participants" id="participants" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}" {{ old('participants', request('participants', 1)) == $i ? 'selected' : '' }}>
                                                    {{ $i }} {{ $i == 1 ? 'person' : 'people' }}
                                                    @if(isset($lesson))
                                                        (€{{ number_format($lesson->price * $i, 2) }})
                                                    @endif
                                                </option>
                                            @endfor
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500">Each participant requires additional information below</p>
                                        @error('participants')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-2">
                                        <label for="notes" class="block text-sm font-medium text-gray-700">Special Requests or Notes</label>
                                        <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-8 p-4 bg-gray-50 rounded-md">
                                    <h4 class="text-base font-medium text-gray-900 mb-2">Participant Information</h4>
                                    <p class="text-sm text-gray-600 mb-4">Please provide details for each participant attending the lesson.</p>

                                    <div id="participants-container">
                                        <!-- First participant form is always shown -->
                                        <div class="participant-form mb-4 p-3 border border-gray-200 rounded-md bg-white">
                                            <h5 class="font-medium text-gray-700 mb-2">Participant 1 (You)</h5>
                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                <div>
                                                    <label for="participant_name_1" class="block text-sm font-medium text-gray-700">Full Name</label>
                                                    <input type="text" name="participant_name[]" id="participant_name_1" value="{{ old('participant_name.0', auth()->user()->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                                </div>
                                                <div>
                                                    <label for="participant_age_1" class="block text-sm font-medium text-gray-700">Age</label>
                                                    <input type="number" name="participant_age[]" id="participant_age_1" min="8" max="99" value="{{ old('participant_age.0') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <div class="flex items-start">
                                                        <div class="flex items-center h-5">
                                                            <input id="participant_experience_1" name="participant_experience[]" type="checkbox" value="1" {{ old('participant_experience.0') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <label for="participant_experience_1" class="font-medium text-gray-700">Has previous kitesurfing experience</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Additional participant forms will be added here -->
                                    </div>

                                    <!-- Hidden template for additional participants -->
                                    <template id="participant-template">
                                        <h5 class="font-medium text-gray-700 mb-2">Participant {num}</h5>
                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            <div>
                                                <label for="participant_name_{num}" class="block text-sm font-medium text-gray-700">Full Name</label>
                                                <input type="text" name="participant_name[]" id="participant_name_{num}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                            </div>

                                            <div>
                                                <label for="participant_age_{num}" class="block text-sm font-medium text-gray-700">Age</label>
                                                <input type="number" name="participant_age[]" id="participant_age_{num}" min="8" max="99" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                            </div>

                                            <div class="sm:col-span-2">
                                                <div class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input id="participant_experience_{num}" name="participant_experience[]" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="participant_experience_{num}" class="font-medium text-gray-700">Has previous kitesurfing experience</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div class="mt-8">
                                    <div class="rounded-md bg-cyan-50 p-4 mb-6">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-cyan-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-cyan-800">Booking Information</h3>
                                                <div class="mt-2 text-sm text-cyan-700">
                                                    <ul class="list-disc pl-5 space-y-1">
                                                        <li>Bookings can be cancelled free of charge up to 48 hours in advance</li>
                                                        <li>Please arrive 30 minutes before your lesson start time</li>
                                                        <li>All participants must be able to swim</li>
                                                        <li>Payment will be processed at the time of booking</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-start mb-6">
                                        <div class="flex items-center h-5">
                                            <input id="terms" name="terms" type="checkbox" value="1" required class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="terms" class="font-medium text-gray-700">I agree to the <a href="#" class="text-cyan-600 hover:text-cyan-500">terms and conditions</a> and <a href="#" class="text-cyan-600 hover:text-cyan-500">privacy policy</a></label>
                                            @error('terms')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <button type="submit" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                                            Book Now
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const participantsSelect = document.getElementById('participants');
            const container = document.getElementById('participants-container');
            const template = document.getElementById('participant-template');

            function updateParticipantForms() {
                // Get selected number of participants
                const count = parseInt(participantsSelect.value) || 1;
                console.log('Updating forms for', count, 'participants');

                // Keep the first participant form (current user)
                const firstForm = container.querySelector('.participant-form');
                if (!firstForm) {
                    console.error('First participant form not found');
                    return;
                }

                // Remove all additional participant forms
                container.querySelectorAll('.participant-form:not(:first-child)').forEach(form => form.remove());

                // Add new forms for additional participants
                for (let i = 2; i <= count; i++) {
                    console.log('Creating form for participant', i);

                    // Clone the template content
                    const newForm = document.createElement('div');
                    newForm.className = 'participant-form mb-4 p-3 border border-gray-200 rounded-md bg-white';

                    // Get template HTML and replace placeholders
                    let formHtml = template.innerHTML;
                    formHtml = formHtml.replace(/{num}/g, i);

                    // Insert the new form HTML
                    newForm.innerHTML = formHtml;
                    container.appendChild(newForm);
                }

                console.log('After update: ', container.querySelectorAll('.participant-form').length, 'total forms');
            }

            // Update forms on participant count change
            participantsSelect.addEventListener('change', updateParticipantForms);

            // Initial form setup
            console.log('Initial form setup');
            updateParticipantForms();

            // Second update after a short delay to ensure proper initialization
            setTimeout(() => {
                console.log('Delayed form update');
                updateParticipantForms();
            }, 100);
        });
    </script>
    @endpush
</x-app-layout>
