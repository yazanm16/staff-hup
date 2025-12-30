@extends('layouts.app')

@section('title', 'Create Permission')

@section('page-title', 'Create Permission')


@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

        <h2 class="text-lg font-bold mb-4">Create Permission</h2>

        <form method="POST" action="{{ route('permissions.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Permission Name</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" placeholder="example: task.create"
                    required>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('permissions.index') }}" class="px-4 py-2 bg-gray-200 rounded">
                    Cancel
                </a>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Save
                </button>
            </div>
        </form>

    </div>
@endsection
