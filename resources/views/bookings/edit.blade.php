<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('bookings.show', $booking) }}" class="text-cyan-600 hover:text-cyan-800">
                    &larr; Back to Booking Details
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Update Booking #{{ $booking->id }}</h3>

                    <form method="POST" action="{{ route('bookings.update', $booking) }}">
                        @csrf
                        @method('PUT')

                        <!-- Lesson Type -->
                        <div class="mb-6">
                            <label for="lesson_id" class="block text-sm font-medium text-gray-700 mb-1">Lesson Type</label>
                            <select id="lesson_id" name="lesson_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-500 focus:ring-opacity-50" required>
                                @foreach($lessons as $lesson)
                                    <option value="{{ $lesson->id }}" {{ $booking->lesson_id == $lesson->id ? 'selected' : '' }}
                                        data-price="{{ $lesson->price }}" data-duration="{{ $lesson->duration }}">
                                        {{ $lesson->name }} - €{{ number_format($lesson->price, 2) }} per person ({{ $lesson->duration }} hours)
                                    </option>
                                @endforeach
                            </select>
                            @error('lesson_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">
                                Changing the lesson type may affect pricing and availability
                            </p>
                        </div>

                        <!-- Date -->
                        <div class="mb-6">
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" id="date" name="date" value="{{ old('date', $booking->date) }}" min="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-500 focus:ring-opacity-50" required>
                            @error('date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Time Slot -->
                        <div class="mb-6">
                            <label for="time_slot" class="block text-sm font-medium text-gray-700 mb-1">Time Slot</label>
                            <select id="time_slot" name="time_slot" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-500 focus:ring-opacity-50" required>
                                <option value="">Select a time slot</option>
                                <!-- Time slots will be populated via AJAX -->
                            </select>
                            @error('time_slot')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            <div id="loading-message" class="hidden mt-2 text-sm text-gray-500">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading available time slots...
                            </div>
                        </div>

                        <!-- Instructor -->
                        <div class="mb-6">
                            <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-1">Instructor</label>
                            <select id="instructor_id" name="instructor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-500 focus:ring-opacity-50" required>
                                <option value="">Select an instructor</option>
                                <!-- Instructors will be populated via AJAX -->
                            </select>
                            @error('instructor_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Number of Participants -->
                        <div class="mb-6">
                            <label for="participants" class="block text-sm font-medium text-gray-700 mb-1">Number of Participants</label>
                            <select id="participants" name="participants" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-500 focus:ring-opacity-50" required>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ $booking->participants == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('participants')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Special Requests -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Special Requests (Optional)</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring focus:ring-cyan-500 focus:ring-opacity-50">{{ old('notes', $booking->notes) }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price Summary -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-md">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Price Summary</h4>
                            <div class="flex justify-between text-sm">
                                <span>Lesson price per person:</span>
                                <span id="price-per-person">€{{ number_format($booking->lesson->price, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm mt-1">
                                <span>Number of participants:</span>
                                <span id="participants-count">{{ $booking->participants }}</span>
                            </div>
                            <div class="flex justify-between font-medium text-sm mt-2 pt-2 border-t border-gray-200">
                                <span>Total price:</span>
                                <span id="total-price">€{{ number_format($booking->total_price, 2) }}</span>
                            </div>
                            <input type="hidden" name="total_price" id="total-price-input" value="{{ $booking->total_price }}">
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('bookings.show', $booking) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                                Update Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lessonSelect = document.getElementById('lesson_id');
            const dateInput = document.getElementById('date');
            const timeSlotSelect = document.getElementById('time_slot');
            const instructorSelect = document.getElementById('instructor_id');
            const participantsSelect = document.getElementById('participants');
            const pricePerPersonElement = document.getElementById('price-per-person');
            const participantsCountElement = document.getElementById('participants-count');
            const totalPriceElement = document.getElementById('total-price');
            const totalPriceInput = document.getElementById('total-price-input');
            const loadingMessage = document.getElementById('loading-message');

            // Initial values
            const initialTimeSlotValue = "{{ $booking->start_time }}-{{ $booking->end_time }}";
            const initialInstructorId = "{{ $booking->instructor_id }}";

            // Function to update the total price
            function updateTotalPrice() {
                const lessonPrice = parseFloat(lessonSelect.options[lessonSelect.selectedIndex].dataset.price);
                const participantsCount = parseInt(participantsSelect.value);

                pricePerPersonElement.textContent = '€' + lessonPrice.toFixed(2);
                participantsCountElement.textContent = participantsCount;

                const totalPrice = lessonPrice * participantsCount;
                totalPriceElement.textContent = '€' + totalPrice.toFixed(2);
                totalPriceInput.value = totalPrice;
            }

            // Function to load available time slots
            function loadTimeSlots() {
                const lessonId = lessonSelect.value;
                const date = dateInput.value;

                if (!lessonId || !date) return;

                // Clear current options and show loading
                timeSlotSelect.innerHTML = '<option value="">Select a time slot</option>';
                instructorSelect.innerHTML = '<option value="">Select an instructor</option>';
                loadingMessage.classList.remove('hidden');

                // Make AJAX request to get available time slots
                fetch(`/api/available-slots?lesson_id=${lessonId}&date=${date}&booking_id=${{{ $booking->id }}}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingMessage.classList.add('hidden');

                        if (data.slots && data.slots.length > 0) {
                            data.slots.forEach(slot => {
                                const option = document.createElement('option');
                                option.value = slot.start_time + '-' + slot.end_time;
                                option.textContent = slot.formatted_time;

                                if (option.value === initialTimeSlotValue) {
                                    option.selected = true;
                                }

                                timeSlotSelect.appendChild(option);
                            });

                            // Trigger time slot change to load instructors
                            loadInstructors();
                        } else {
                            timeSlotSelect.innerHTML = '<option value="">No available time slots</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading time slots:', error);
                        loadingMessage.classList.add('hidden');
                        timeSlotSelect.innerHTML = '<option value="">Error loading time slots</option>';
                    });
            }

            // Function to load available instructors
            function loadInstructors() {
                const lessonId = lessonSelect.value;
                const date = dateInput.value;
                const timeSlot = timeSlotSelect.value;

                if (!lessonId || !date || !timeSlot) return;

                const [startTime, endTime] = timeSlot.split('-');

                // Clear current options and show loading
                instructorSelect.innerHTML = '<option value="">Select an instructor</option>';
                loadingMessage.classList.remove('hidden');

                // Make AJAX request to get available instructors
                fetch(`/api/available-instructors?lesson_id=${lessonId}&date=${date}&start_time=${startTime}&end_time=${endTime}&booking_id=${{{ $booking->id }}}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingMessage.classList.add('hidden');

                        if (data.instructors && data.instructors.length > 0) {
                            data.instructors.forEach(instructor => {
                                const option = document.createElement('option');
                                option.value = instructor.id;
                                option.textContent = instructor.name;
