<!-- employees/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Employee')
@section('page-title', 'Edit Employee')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Edit Employee Information</h3>
                    <span class="text-sm text-gray-500">ID: {{ $employee->id }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('employees.update', $employee) }}" class="p-6 space-y-6"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Info -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-700">Personal Information</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text"
                                name="name"class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ $employee->name }}">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" name="email"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ $employee->email }}">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />

                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ $employee->phone }}">
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />

                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Image Profile</label>
                            @if ($employee->photo)
                                <img src="{{ asset('storage/' . $employee->photo->path) }}"
                                    class="w-20 h-20 rounded-full mb-3 object-cover">
                            @endif
                            <input type="file" name="image"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Employment Info -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-700">Employment Information</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                            <select name="department_id"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('department_id') }}">
                                <option value="">Select Department</option>
                                @if (count($departments) > 0)
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ $department->id == $employee->department_id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                            <input type="text" name="position"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ $employee->position }}">
                            <x-input-error :messages="$errors->get('position')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Role <span class="text-red-500">*</span>
                            </label>

                            <select name="role"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                                <option value="">Select Role</option>

                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ $employee->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach

                            </select>

                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('employees.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
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
