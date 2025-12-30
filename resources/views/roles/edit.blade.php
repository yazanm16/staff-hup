@extends('layouts.app')

@section('title', 'Edit Role')
@section('page-title', 'Edit Role')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">

        <h2 class="text-xl font-bold mb-6">
            Edit Role: {{ $role->name }}
        </h2>

        <form method="POST" action="{{ route('roles.update', $role) }}">
            @csrf
            @method('PUT')

            <!-- Role Name -->
            <div class="mb-4">
                <label class="block font-semibold mb-1">Role Name</label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}"
                    class="w-full border rounded px-3 py-2" required>
                @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Permissions -->
            <div class="mb-6">
                <label class="block font-semibold mb-2">
                    Permissions
                </label>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                @checked(in_array($permission->name, $rolePermissions))>
                            <span>{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center">
                <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-200 rounded">
                    Cancel
                </a>

                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded">
                    Update Role
                </button>
            </div>
        </form>

        <!-- Delete Role -->
        <hr class="my-6">

        <form method="POST" action="{{ route('roles.destroy', $role) }}"
            onsubmit="return confirm('Are you sure you want to delete this role?')">
            @csrf
            @method('DELETE')

            <button class="text-red-600">
                Delete Role
            </button>
        </form>

    </div>
@endsection
