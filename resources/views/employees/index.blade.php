<!-- employees/index.blade.php -->
@extends('layouts.app')

@section('title', 'Employees')
@section('page-title', 'Employees Management')

@section('content')
    <div class="space-y-6">
        <!-- Header with Add Button -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Employees</h2>
                <p class="text-gray-600">Manage your team members</p>
            </div>
            <a href="{{ route('employees.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Add Employee
            </a>
        </div>

        @if (session('message'))
            <div
                class="mb-4 px-4 py-3 rounded-lg
        @if (session('type') === 'success') bg-green-100 text-green-800
        @elseif (session('type') === 'warning')
            bg-yellow-100 text-yellow-800
        @else
            bg-red-100 text-red-800 @endif
    ">
                {{ session('message') }}
            </div>
        @endif
        <!-- Employees Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if (count($users) > 0)
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                                @if ($user->photo && file_exists(public_path('storage/' . $user->photo->path)))
                                                    <img class="w-full h-full object-cover"
                                                        src="{{ asset('storage/' . $user->photo->path) }}" alt="User Image">
                                                @else
                                                    <i class="fas fa-user text-blue-600"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-medium">{{ $user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $user->department->name ?? 'No Department' }}</span>
                                    </td>
                                    <td class="px-6 py-4">{{ $user->position }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $user->getRoleNames()->first() }}</span>
                                    </td>

                                    {{-- Action --}}
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('employees.edit', $user) }}"
                                                class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('employees.destroy', $user) }}" method="POST"
                                                onsubmit="return confirm('Are you sure to delete this Employee {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t">
                {{ $users->links('vendor.pagination.tailwind') }}
            </div>

        </div>
    </div>
@endsection
