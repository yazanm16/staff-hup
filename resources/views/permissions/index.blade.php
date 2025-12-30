@extends('layouts.app')

@section('title', 'Permissions')
@section('page-title', 'Permissions Management')


@section('content')
    <div class="max-w-5xl mx-auto space-y-4">

        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold">Permissions</h2>
            @if (session('message'))
                <div
                    class="mb-4 px-4 py-3 rounded-lg
        @if (session('type') === 'success') bg-green-100 text-green-800
        @elseif (session('type') === 'warning')
            bg-yellow-100 text-yellow-800
        @else
            bg-red-100 text-red-800 @endif
    ">
                    {{ session('message') }}
                </div>
            @endif
            <a href="{{ route('permissions.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">
                + New Permission
            </a>
        </div>

        <div class="bg-white shadow rounded divide-y">
            @foreach ($permissions as $permission)
                <div class="p-4 flex justify-between items-center">
                    <span>{{ $permission->name }}</span>

                    <div class="flex gap-2">
                        <a href="{{ route('permissions.edit', $permission) }}" class="text-blue-600 text-sm">
                            Edit
                        </a>

                        <form method="POST" action="{{ route('permissions.destroy', $permission) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 text-sm" onclick="return confirm('Delete permission?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
