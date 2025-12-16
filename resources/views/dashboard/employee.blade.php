<!-- dashboard/employee.blade.php -->
@extends('layouts.app')

@section('title', 'Employee Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h2>
                    <p class="text-gray-600 mt-2">Here's your overview</p>
                </div>
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    @if (auth()->user()->image && file_exists(public_path('storage/employees/' . auth()->user()->image)))
                        <img class="w-full h-full object-cover"
                            src="{{ asset('storage/employees/' . auth()->user()->image) }}" alt="User Image">
                    @else
                        <i class="fas fa-user text-blue-600"></i>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-tasks text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Assigned Tasks</p>
                        <p class="text-2xl font-bold mt-1">{{ $numTasks }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Completed</p>
                        <p class="text-2xl font-bold mt-1">{{ $completedTasks }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Monthly Attendance Days</p>
                        <p class="text-2xl font-bold mt-1">{{ $attendanceDays }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Tasks -->
        <div class="bg-white rounded-lg shadow">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="font-semibold text-gray-800">Today's Tasks</h3>
                <a href="{{ route('tasks.my') }} " class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">All
                    Task</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due
                                Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if (count($myTasks) > 0)

                            @foreach ($myTasks as $task)
                                <tr>
                                    <td class="px-6 py-4">{{ $task->title }}</td>
                                    <td class="px-6 py-4">{{ $task->due_date }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $task->status }}
                                        </span>
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
