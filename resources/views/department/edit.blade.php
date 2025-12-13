<!-- employees/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit department')
@section('page-title', 'Edit department')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Edit department Information</h3>
                    <span class="text-sm text-gray-500">ID:{{ $department->id }}</span>
                </div>
            </div>
            @if (session('DepartmentUpdateStatus'))
                <div class="alert alert-success">
                    {{ session('DepartmentUpdateStatus') }}
                </div>
            @endif
            <form method="POST" action="{{ route('departments.update', $department) }}" class="p-6 space-y-6"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Department Info -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Department Name</label>
                            <input type="text"
                                name="name"class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ $department->name }}">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('departments.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
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
