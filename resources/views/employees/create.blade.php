<!-- employees/create.blade.php -->
@extends('layouts.app')

@section('title', 'Add Employee')
@section('page-title', 'Add New Employee')

@section('content')
    <div class="max-w-2xl mx-auto">
        @if (session('EmployeeCreateStatus'))
            <div class="alert alert-success">
                {{ session('EmployeeCreateStatus') }}
            </div>
        @endif
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h3 class="font-semibold text-gray-800">Employee Information</h3>
            </div>
            <form method="POST" action="{{ route('employees.store') }}" class="p-6 space-y-6" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Info -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-700">Personal Information</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text"
                                name="name"class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('name') }}">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" name="email"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('email') }}">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />

                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('phone') }}">
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />

                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" name="password"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Image Profile</label>
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
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                            <input type="text" name="position"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('position') }}">
                            <x-input-error :messages="$errors->get('position')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                            <select name="role" required
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('role') }}">
                                <option value="employee">Employee</option>
                                <option value="admin">Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>


                    </div>
                </div>

                <!-- Address -->
                {{-- <div class="space-y-4">
                    <h4 class="font-medium text-gray-700">Address</h4>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                        <input type="text" name="address"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" name="city"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                            <input type="text" name="state"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                            <input type="text" name="zip_code"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div> --}}

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('employees.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Create Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
