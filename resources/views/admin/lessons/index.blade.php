@extends('layouts.admin')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Lesson Management</h1>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <i class="fas fa-graduation-cap"></i>
                <span>Total Lessons: {{ $lessons->total() }}</span>
            </div>
        </div>
        <p class="mt-1 text-gray-600">Manage kitesurfing lessons, difficulty levels, and pricing</p>
    </div>

    <!-- Lessons Table Card -->
    <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
        <!-- Card Header with Gradient -->
        <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-600 p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">All Lessons</h2>
                    <p class="text-blue-100 mt-1">Configure lesson types and pricing</p>
                </div>
                <a href="{{ route('admin.lessons.create') }}"
                   class="bg-white text-blue-700 hover:bg-blue-50 px-6 py-3 rounded-lg shadow-md font-semibold flex items-center transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> Add New Lesson
                </a>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="py-4 px-6 text-left font-bold text-sm text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-water mr-2 text-gray-500"></i>Lesson Name
                        </th>
                        <th class="py-4 px-6 text-left font-bold text-sm text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-clock mr-2 text-gray-500"></i>Duration
                        </th>
                        <th class="py-4 px-6 text-left font-bold text-sm text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-euro-sign mr-2 text-gray-500"></i>Price
                        </th>
                        <th class="py-4 px-6 text-left font-bold text-sm text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-chart-line mr-2 text-gray-500"></i>Difficulty
                        </th>
                        <th class="py-4 px-6 text-left font-bold text-sm text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-users mr-2 text-gray-500"></i>Max Participants
                        </th>
                        <th class="py-4 px-6 text-left font-bold text-sm text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2 text-gray-500"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($lessons as $lesson)
                    <tr class="hover:bg-blue-50 transition-all duration-200 hover:shadow-sm">
                        <td class="py-5 px-6">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-cyan-500 to-blue-500 flex items-center justify-center text-white font-bold mr-4">
                                    <i class="fas fa-wind"></i>
                                </div>
                                <div>
                                    <div class="text-gray-900 font-semibold text-lg">{{ $lesson->name }}</div>
                                    @if($lesson->description)
                                        <div class="text-gray-500 text-sm">{{ Str::limit($lesson->description, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <div class="flex items-center">
                                <i class="fas fa-stopwatch mr-2 text-blue-500"></i>
                                <span class="text-gray-800 font-medium">{{ $lesson->duration }} min</span>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <div class="flex items-center">
                                <span class="text-2xl font-bold text-green-600">€{{ number_format($lesson->price, 2) }}</span>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold shadow-sm
                                @if($lesson->difficulty_level == 'beginner') bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300
                                @elseif($lesson->difficulty_level == 'intermediate') bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300
                                @elseif($lesson->difficulty_level == 'advanced') bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300
                                @else bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border border-blue-300
                                @endif">
                                @if($lesson->difficulty_level == 'beginner')
                                    <i class="fas fa-seedling mr-1"></i>
                                @elseif($lesson->difficulty_level == 'intermediate')
                                    <i class="fas fa-chart-line mr-1"></i>
                                @elseif($lesson->difficulty_level == 'advanced')
                                    <i class="fas fa-fire mr-1"></i>
                                @else
                                    <i class="fas fa-star mr-1"></i>
                                @endif
                                {{ ucfirst($lesson->difficulty_level) }}
                            </span>
                        </td>
                        <td class="py-5 px-6">
                            <div class="flex items-center">
                                <i class="fas fa-user-friends mr-2 text-indigo-500"></i>
                                <span class="text-gray-800 font-bold text-lg">{{ $lesson->max_participants }}</span>
                                <span class="text-gray-500 text-sm ml-1">people</span>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.lessons.edit', $lesson) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit
                                </a>
                                <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this lesson? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                        <i class="fas fa-trash mr-2"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="text-gray-400">
                                <i class="fas fa-graduation-cap text-4xl mb-4"></i>
                                <p class="text-lg font-medium">No lessons found</p>
                                <p class="text-sm">Get started by adding your first lesson</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($lessons->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Showing {{ $lessons->firstItem() }} to {{ $lessons->lastItem() }} of {{ $lessons->total() }} lessons
                </div>
                <div class="pagination-wrapper">
                    {{ $lessons->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Custom Pagination Styles -->
    <style>
        .pagination-wrapper .pagination {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 0.5rem;
        }

        .pagination-wrapper .page-item .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.75rem;
            margin: 0;
            color: #6b7280;
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
        }

        .pagination-wrapper .page-item .page-link:hover {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        }

        .pagination-wrapper .page-item.active .page-link {
            background-color: #1d4ed8;
            color: white;
            border-color: #1d4ed8;
            box-shadow: 0 2px 4px rgba(29, 78, 216, 0.3);
        }

        .pagination-wrapper .page-item.disabled .page-link {
            color: #9ca3af;
            background-color: #f3f4f6;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }
    </style>
@endsection
