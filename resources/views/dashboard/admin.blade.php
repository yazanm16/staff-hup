<!-- dashboard/admin.blade.php -->
@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Employees</p>
                        <p class="text-3xl font-bold mt-2">{{ count($users) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Active Tasks</p>
                        <p class="text-3xl font-bold mt-2">{{ $inProgressTasks + $pendingTasks }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-tasks text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Today Present</p>
                        <p class="text-3xl font-bold mt-2">{{ $todayPresent }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pending Tasks</p>
                        <p class="text-3xl font-bold mt-2">{{ $pendingTasks }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">Recent Completed Task</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Task</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @if (count($completedTasks) > 0)
                                @foreach ($completedTasks as $task)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $task->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $task->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $task->status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">Task Status</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Completed</span>
                                <span>{{ $completedPercent }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $completedPercent }}%">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>In Progress</span>
                                <span>{{ $inProgressPercent }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $inProgressPercent }}%">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Pending</span>
                                <span>{{ $pendingPercent }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $pendingPercent }}%">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
