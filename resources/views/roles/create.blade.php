@extends('layouts.app')

@section('title', 'Create Role')
@section('page-title', 'Create Role')

@section('content')
    <div class="max-w-4xl mx-auto px-4">

        <div class="mb-6">
            <h2 class="text-xl font-bold">Create New Role</h2>
            <p class="text-sm text-gray-500">Create a role and assign permissions</p>
        </div>

        <div class="bg-white p-6 rounded shadow">

            <form method="POST" action="{{ route('roles.store') }}">
                @csrf

                {{-- Role Name --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-1">
                        Role Name
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" placeholder="e.g. manager">

                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Permissions --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">
                        Permissions
                    </label>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                    class="rounded border-gray-300">
                                {{ $permission->name }}
                            </label>
                        @endforeach
                    </div>

                    @error('permissions')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </a>

                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Create Role
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection
