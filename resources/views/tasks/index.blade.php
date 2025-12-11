<!-- tasks/index.blade.php -->
@extends('layouts.app')

@section('title', 'Tasks')
@section('page-title', 'Tasks Management')

@section('content')
    <div class="space-y-6">
        <!-- Header with Actions -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tasks</h2>
                <p class="text-gray-600">Manage and track tasks</p>
            </div>
            @if (auth()->user()->role === 'admin')
                <div class="flex space-x-3">
                    <a href="{{ route('tasks.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Create Task
                    </a>
                </div>
            @endif
        </div>

        <!-- Tabs -->
        <div class="border-b">
            <nav class="flex space-x-8">
                <a href="#" class="py-3 px-1 border-b-2 border-blue-600 text-blue-600 font-medium">
                    All Tasks (32)
                </a>
                <a href="#" class="py-3 px-1 text-gray-500 hover:text-gray-700">
                    In Progress (12)
                </a>
                <a href="#" class="py-3 px-1 text-gray-500 hover:text-gray-700">
                    Completed (18)
                </a>
                <a href="#" class="py-3 px-1 text-gray-500 hover:text-gray-700">
                    Overdue (2)
                </a>
            </nav>
        </div>

        <!-- Tasks Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task
                            </th>
                            @if (auth()->user()->role === 'admin')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Assigned To</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due
                                Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @for ($i = 0; $i < 6; $i++)
                            <tr>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium">Complete project documentation</p>
                                        <p class="text-sm text-gray-500 mt-1">Write and submit final project documentation
                                        </p>
                                    </div>
                                </td>
                                @if (auth()->user()->role === 'admin')
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-2">
                                                <i class="fas fa-user text-gray-600 text-sm"></i>
                                            </div>
                                            <span>John Doe</span>
                                        </div>
                                    </td>
                                @endif
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800">High</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="{{ $i % 3 == 0 ? 'text-red-600' : 'text-gray-700' }}">Dec 15, 2023</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($i % 3 == 0)
                                        <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">In
                                            Progress</span>
                                    @elseif($i % 3 == 1)
                                        <span
                                            class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">Completed</span>
                                    @else
                                        <span
                                            class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @if (auth()->user()->role === 'employee')
                                            <button class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
