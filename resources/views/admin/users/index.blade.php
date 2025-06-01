@extends('layouts.admin')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <i class="fas fa-users"></i>
                <span>Total Users: {{ $users->total() }}</span>
            </div>
        </div>
        <p class="mt-1 text-gray-600">Manage system users, roles, and permissions</p>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
        <!-- Card Header with Gradient -->
        <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-600 p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">All Users</h2>
                    <p class="text-blue-100 mt-1">Manage and monitor user accounts</p>
                </div>
                <a href="{{ route('admin.users.create') }}"
                   class="bg-white text-blue-700 hover:bg-blue-50 px-6 py-3 rounded-lg shadow-md font-semibold flex items-center transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i> Add New User
                </a>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="py-4 px-6 text-left font-bold text-sm text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-2 text-gray-500"></i>ID
                        </th>
                        <th class="py-4 px-6 text-left font-bold text-sm text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-user mr-2 text-gray-500"></i>Name
                        </th>
                        <th class="py-4 px-6 text-left font-bold text-sm text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-envelope mr-2 text-gray-500"></i>Email
                        </th>
                        <th class="py-4 px-6 text-left font-bold text-sm text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-shield-alt mr-2 text-gray-500"></i>Role
                        </th>
                        <th class="py-4 px-6 text-left font-bold text-sm text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-phone mr-2 text-gray-500"></i>Phone
                        </th>
                        <th class="py-4 px-6 text-left font-bold text-sm text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-star mr-2 text-gray-500"></i>Experience
                        </th>
                        <th class="py-4 px-6 text-left font-bold text-sm text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2 text-gray-500"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-blue-50 transition-all duration-200 hover:shadow-sm">
                        <td class="py-5 px-6 text-gray-800 font-bold text-lg">#{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-5 px-6">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold mr-4">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-gray-900 font-semibold">{{ $user->name }}</div>
                                    <div class="text-gray-500 text-sm">Member since {{ $user->created_at->format('M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <div class="text-gray-800 font-medium">{{ $user->email }}</div>
                        </td>
                        <td class="py-5 px-6">
                            @if($user->role)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold shadow-sm
                                    @if($user->role->name == 'admin') bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300
                                    @elseif($user->role->name == 'instructor') bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border border-blue-300
                                    @else bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300
                                    @endif">
                                    @if($user->role->name == 'admin')
                                        <i class="fas fa-crown mr-1"></i>
                                    @elseif($user->role->name == 'instructor')
                                        <i class="fas fa-chalkboard-teacher mr-1"></i>
                                    @else
                                        <i class="fas fa-user mr-1"></i>
                                    @endif
                                    {{ ucfirst($user->role->name) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gray-100 text-gray-800 border border-gray-300">
                                    <i class="fas fa-question-circle mr-1"></i>
                                    No Role
                                </span>
                            @endif
                        </td>
                        <td class="py-5 px-6">
                            <div class="text-gray-800">
                                @if($user->phone)
                                    <i class="fas fa-phone-alt mr-1 text-green-500"></i>
                                    {{ $user->phone }}
                                @else
                                    <span class="text-gray-400">
                                        <i class="fas fa-minus mr-1"></i>
                                        Not provided
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            @if($user->experience_level)
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-medal mr-1"></i>
                                    {{ ucfirst($user->experience_level) }}
                                </span>
                            @else
                                <span class="text-gray-400">
                                    <i class="fas fa-minus mr-1"></i>
                                    Not set
                                </span>
                            @endif
                        </td>
                        <td class="py-5 px-6">
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
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
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p class="text-lg font-medium">No users found</p>
                                <p class="text-sm">Get started by adding your first user</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                </div>
                <div class="pagination-wrapper">
                    {{ $users->links() }}
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
