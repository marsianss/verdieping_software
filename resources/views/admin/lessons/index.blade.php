@extends('layouts.admin')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manage Lessons</h1>
            <a href="{{ route('admin.lessons.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i> Add New Lesson
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th class="py-3 px-4 text-left">Name</th>
                        <th class="py-3 px-4 text-left">Duration</th>
                        <th class="py-3 px-4 text-left">Price</th>
                        <th class="py-3 px-4 text-left">Difficulty</th>
                        <th class="py-3 px-4 text-left">Max Participants</th>
                        <th class="py-3 px-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($lessons as $lesson)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $lesson->id }}</td>
                        <td class="py-3 px-4">{{ $lesson->name }}</td>
                        <td class="py-3 px-4">{{ $lesson->duration }} min</td>
                        <td class="py-3 px-4">€{{ $lesson->price }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($lesson->difficulty_level == 'beginner') bg-green-100 text-green-800
                                @elseif($lesson->difficulty_level == 'intermediate') bg-yellow-100 text-yellow-800
                                @elseif($lesson->difficulty_level == 'advanced') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ ucfirst($lesson->difficulty_level) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">{{ $lesson->max_participants }}</td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.lessons.edit', $lesson) }}" class="text-blue-500 hover:underline">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this lesson?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $lessons->links() }}
        </div>
    </div>
@endsection