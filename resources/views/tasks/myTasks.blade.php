@extends('layouts.app')

@section('title', 'My Tasks')
@section('page-title', 'My Tasks')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-lg shadow">
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
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold text-gray-800">My Tasks</h2>
            </div>

            @if ($tasks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Task</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @foreach ($tasks as $task)
                                <tr>

                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-medium">{{ $task->title }}</p>
                                            <p class="text-sm text-gray-500 mt-1">{{ $task->description }}
                                            </p>
                                        </div>
                                    <td class="px-6 py-4 space-y-2">

                                        {{-- Status Badge --}}
                                        @if ($task->status === 'Completed')
                                            <span
                                                class="inline-block px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">
                                                Completed
                                            </span>
                                        @elseif ($task->status === 'In-Progress')
                                            <span
                                                class="inline-block px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                                                In Progress
                                            </span>
                                        @else
                                            <span
                                                class="inline-block px-3 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-full">
                                                Pending
                                            </span>
                                        @endif

                                        {{-- Status Select --}}
                                        <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <select name="status" onchange="this.form.submit()"
                                                class="mt-1 block w-36 border-gray-300 rounded-md shadow-sm text-sm
                                                 focus:ring-blue-500 focus:border-blue-500">

                                                <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>

                                                <option value="In-Progress"
                                                    {{ $task->status === 'In-Progress' ? 'selected' : '' }}>
                                                    In Progress
                                                </option>

                                                <option value="Completed"
                                                    {{ $task->status === 'Completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                            </select>
                                        </form>
                                    </td>


                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center text-gray-500">
                    You️ No tasks assigned to you.
                </div>
            @endif

        </div>
    </div>
@endsection
