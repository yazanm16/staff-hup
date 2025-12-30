@extends('layouts.app')

@section('title', 'Roles Management')
@section('page-title', 'Roles Management')

@section('content')
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Roles Management</h2>
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
            @can('role.manage')
                <a href="{{ route('roles.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Create New Role
                </a>
            @endcan
        </div>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full table-fixed border-collapse">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="w-16 px-4 py-3">#</th>
                        <th class="w-40 px-4 py-3">Role Name</th>
                        <th class="px-4 py-3">Permissions</th>
                        <th class="w-32 px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($roles as $index => $role)
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>

                            <td class="px-4 py-3 font-semibold capitalize">
                                {{ $role->name }}
                            </td>

                            {{-- Permissions --}}
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($role->permissions as $permission)
                                        <span class="px-2 py-1 text-xs rounded bg-gray-200 text-gray-700">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-3">
                                    @can('role.manage')
                                        <a href="{{ route('roles.edit', $role) }}" class="text-blue-600 hover:underline">
                                            Edit
                                        </a>

                                        <form action="{{ route('roles.destroy', $role) }}" method="POST"
                                            onsubmit="return confirm('Delete this role?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:underline">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
