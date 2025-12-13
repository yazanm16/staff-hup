<!-- employees/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Task')
@section('page-title', 'Edit Task')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Edit Task Information</h3>
                    <span class="text-sm text-gray-500">ID:{{ $task->id }}</span>
                </div>
            </div>


            <form method="POST" action="{{ route('tasks.update', $task) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Task Title *</label>
                        <input type="text" name="title"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter task title" value="{{ $task->title }}">
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />

                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Describe the task">{{ $task->description }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assign To *</label>
                            <select name="user_id"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Employee</option>
                                @if (count($users) > 0)
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $user->id == $task->user_id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />

                        </div>


                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Due Date </label>
                            <input type="date" name="due_date"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ $task->due_date }}">
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />

                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="In-Progress" {{ $task->status === 'In-Progress' ? 'selected' : '' }}>
                                    In-Progress</option>
                                <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('tasks.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
