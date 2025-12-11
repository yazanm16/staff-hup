<!-- tasks/create.blade.php -->
@extends('layouts.app')

@section('title', 'Create Task')
@section('page-title', 'Create New Task')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h3 class="font-semibold text-gray-800">Create New Task</h3>
            </div>

            <form method="POST" action="{{ route('tasks.store') }}" class="p-6 space-y-6">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Task Title *</label>
                        <input type="text" name="title" required
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter task title">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Describe the task"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assign To *</label>
                            <select name="assigned_to" required
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Employee</option>
                                @for ($i = 0; $i < 5; $i++)
                                    <option value="{{ $i }}">John Doe (Engineering)</option>
                                    <option value="{{ $i }}">Jane Smith (Marketing)</option>
                                    <option value="{{ $i }}">Mike Johnson (Sales)</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                            <select name="priority" required
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high" selected>High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Due Date *</label>
                            <input type="date" name="due_date" required
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pending">Pending</option>
                                <option value="in_progress" selected>In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="on_hold">On Hold</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('tasks.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
