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

        {{-- <!-- Tabs -->
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
        </div> --}}

        <!-- Tasks Table -->
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
                                Created
                                AT</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due
                                Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if (count($tasks) > 0)
                            @foreach ($tasks as $task)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-medium">{{ $task->title }}</p>
                                            <p class="text-sm text-gray-500 mt-1">{{ $task->description }}
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
                                                <span>{{ $task->User->name }}</span>
                                            </div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700 ">{{ $task->created_at->format('Y-m-d') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700 ">{{ $task->due_date }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($task->status == 'In-Progress')
                                            <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">In
                                                Progress</span>
                                        @elseif($task->status == 'Completed')
                                            <span
                                                class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">Completed</span>
                                        @else
                                            <span
                                                class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('tasks.edit', $task) }}"
                                                class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                                onsubmit="return confirm('Are you sure to delete this {{ $task->name }} Task ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            {{-- @if (auth()->user()->role === 'employee') 
                                            <button class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check"></i>
                                            </button>
                                             @endif --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
