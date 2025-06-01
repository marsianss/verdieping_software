<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($lesson) ? __('Edit Lesson') : __('Create Lesson') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ isset($lesson) ? route('admin.lessons.update', $lesson) : route('admin.lessons.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($lesson))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                            <div>
                                <x-label for="name" :value="__('Lesson Name')" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', isset($lesson) ? $lesson->name : '')" required autofocus />
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="difficulty_level" :value="__('Difficulty Level')" />
                                <select id="difficulty_level" name="difficulty_level" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50">
                                    <option value="beginner" {{ old('difficulty_level', isset($lesson) ? $lesson->difficulty_level : '') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ old('difficulty_level', isset($lesson) ? $lesson->difficulty_level : '') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ old('difficulty_level', isset($lesson) ? $lesson->difficulty_level : '') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                    <option value="expert" {{ old('difficulty_level', isset($lesson) ? $lesson->difficulty_level : '') == 'expert' ? 'selected' : '' }}>Expert</option>
                                </select>
                                @error('difficulty_level')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="duration" :value="__('Duration (minutes)')" />
                                <x-input id="duration" class="block mt-1 w-full" type="number" name="duration" min="30" step="30" :value="old('duration', isset($lesson) ? $lesson->duration : 120)" required />
                                @error('duration')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="price" :value="__('Price (€)')" />
                                <x-input id="price" class="block mt-1 w-full" type="number" name="price" min="0" step="0.01" :value="old('price', isset($lesson) ? $lesson->price : 75)" required />
                                @error('price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="max_participants" :value="__('Maximum Participants')" />
                                <x-input id="max_participants" class="block mt-1 w-full" type="number" name="max_participants" min="1" max="20" :value="old('max_participants', isset($lesson) ? $lesson->max_participants : 6)" required />
                                @error('max_participants')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="equipment_provided" :value="__('Equipment Provided')" />
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" class="rounded border-gray-300 text-cyan-600 shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50" name="equipment_provided" value="1" {{ old('equipment_provided', isset($lesson) && $lesson->equipment_provided ? 'checked' : '') }}>
                                        <span class="ml-2 text-sm text-gray-600">{{ __('All equipment is provided') }}</span>
                                    </label>
                                </div>
                                @error('equipment_provided')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <x-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50" required>{{ old('description', isset($lesson) ? $lesson->description : '') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="image" :value="__('Lesson Image')" />
                                <input id="image" name="image" type="file" class="block mt-1 w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-cyan-50 file:text-cyan-700
                                    hover:file:bg-cyan-100">
                                @if(isset($lesson) && $lesson->image_path)
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-500">Current image:</p>
                                        <img src="{{ asset('storage/' . $lesson->image_path) }}" alt="{{ $lesson->name }}" class="h-20 w-auto mt-1 rounded">
                                    </div>
                                @endif
                                @error('image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="image_url" :value="__('Lesson Image URL')" />
                                <input id="image_url" name="image_url" type="text"
                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                                    placeholder="/images/lessons/custom-image.jpg"
                                    value="{{ old('image_url', $lesson->image_url ?? '') }}">
                                <p class="mt-1 text-sm text-gray-500">
                                    Leave empty to use default image based on difficulty level
                                </p>
                                @if(isset($lesson) && $lesson->image_url)
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-500">Current image:</p>
                                        <img src="{{ $lesson->image_url }}" alt="{{ $lesson->name }}" class="h-20 w-auto mt-1 rounded">
                                    </div>
                                @endif
                                @error('image_url')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="active" :value="__('Status')" />
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio text-cyan-600" name="active" value="1" {{ old('active', isset($lesson) ? $lesson->active : true) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">Active</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6">
                                        <input type="radio" class="form-radio text-red-600" name="active" value="0" {{ old('active', isset($lesson) ? $lesson->active : true) ? '' : 'checked' }}>
                                        <span class="ml-2 text-sm text-gray-600">Inactive</span>
                                    </label>
                                </div>
                                @error('active')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('admin.lessons.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-button class="ml-3">
                                {{ isset($lesson) ? __('Update Lesson') : __('Create Lesson') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
