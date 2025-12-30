@extends('layouts.app')

@section('title', 'Deleted Comments')
@section('page-title', 'Deleted Comments')

@section('content')
    <div class="max-w-5xl mx-auto space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold mb-4">
                Deleted Comments – {{ $task->title }}
            </h2>
            <a href="{{ route('tasks.comments.index', $task->id) }}"
                class="px-4 py-2 bg-blue-100 rounded hover:bg-gray-200 text-sm">
                ← Back
            </a>
        </div>
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
        @forelse ($comments as $comment)
            <div class="bg-white p-4 rounded shadow flex justify-between">
                <div>
                    <p class="text-sm text-gray-500">
                        {{ $comment->user->name }}
                    </p>
                    @if ($comment->body)
                        <p class="mt-3 text-gray-700">
                            {{ $comment->body }}
                        </p>
                    @endif

                    @if ($comment->photo)
                        <img src="{{ asset('storage/' . $comment->photo->path) }}" class="mt-3 max-w-xs rounded border">
                    @endif
                </div>

                <div class="flex gap-2">
                    @can('restore', $comment)
                        <form method="POST" action="{{ route('comments.restore', $comment->id) }}">
                            @csrf
                            <button class="text-green-600">Restore</button>
                        </form>
                    @endcan

                    @can('forceDelete', $comment)
                        <form method="POST" action="{{ route('comments.forceDelete', $comment->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">Delete Forever</button>
                        </form>
                    @endcan
                </div>
            </div>
        @empty
            <p class="text-gray-500">No deleted comments.</p>
        @endforelse


    </div>
@endsection
