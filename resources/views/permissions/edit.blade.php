@extends('layouts.app')

@section('title', 'Edit Permission')
@section('page-title', 'Edit Permission')


@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

        <h2 class="text-lg font-bold mb-4">Edit Permission</h2>

        <form method="POST" action="{{ route('permissions.update', $permission) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Permission Name</label>
                <input type="text" name="name" value="{{ $permission->name }}" class="w-full border rounded px-3 py-2"
                    required>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('permissions.index') }}" class="px-4 py-2 bg-gray-200 rounded">
                    Cancel
                </a>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Update
                </button>
            </div>
        </form>

    </div>
@endsection
