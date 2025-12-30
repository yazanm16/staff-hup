@extends('layouts.app')

@section('title', 'Task Comments')
@section('page-title', 'Task Comments')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Task Info -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        Task:{{ $task->title }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        Due: {{ $task->due_date ?? '-' }}
                    </p>
                </div>

                <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200 text-sm">
                    ← Back
                </a>
            </div>
            @can('comment.view-deleted')
                <a href="{{ route('tasks.comments.deleted', $task) }}" class="text-sm text-red-600 hover:underline">
                    View deleted comments
                </a>
            @endcan

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
        </div>
        <!-- Comments List -->
        <div class="bg-white rounded-lg shadow divide-y">

            @forelse ($comments as $comment)
                <div class="p-6 flex gap-4">

                    <!-- User Image -->
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                        @if ($comment->user?->photo)
                            <img src="{{ asset('storage/' . $comment->user->photo->path) }}"
                                class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user text-gray-500"></i>
                        @endif
                    </div>


                    <!-- Comment Content -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ $comment->user->name }}
                                    <span class="text-xs text-gray-500 capitalize">
                                        ({{ $comment->user->getRoleNames()->first() }})
                                    </span>
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $comment->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                @can('delete', $comment)
                                    <form method="POST" action="{{ route('tasks.comments.destroy', [$task, $comment]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 text-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                                @can('update', $comment)
                                    <button onclick="toggleEdit({{ $comment->id }})"
                                        class="text-blue-500 hover:text-blue-700 text-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endcan
                            </div>
                        </div>

                        @if ($comment->body)
                            <p class="mt-3 text-gray-700">
                                {{ $comment->body }}
                            </p>
                            <form id="edit-{{ $comment->id }}"
                                action="{{ route('tasks.comments.update', [$task, $comment]) }}" method="POST"
                                class="hidden mt-3">
                                @csrf
                                @method('PUT')

                                <textarea name="body" rows="3" class="w-full border rounded-lg px-3 py-2">{{ $comment->body }}</textarea>

                                <div class="mt-2 flex gap-2">
                                    <button class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Save</button>
                                    <button type="button" onclick="toggleEdit({{ $comment->id }})"
                                        class="text-sm text-gray-500">Cancel</button>
                                </div>
                            </form>
                        @endif

                        @if ($comment->photo)
                            <img src="{{ asset('storage/' . $comment->photo->path) }}"
                                class="mt-3 max-w-xs rounded border">
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    No comments yet.
                </div>
            @endforelse

        </div>

        <!-- Add Comment -->
        @can('create', App\Models\Comment::class)
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-semibold text-gray-800 mb-4">Add Comment</h3>

                <form method="POST" action="{{ route('tasks.comments.store', $task) }}" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf

                    <div>
                        <textarea name="body" rows="3" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                            placeholder="Write your comment..."></textarea>
                    </div>

                    <div>
                        <input type="file" name="image" class="text-sm text-gray-600">
                    </div>

                    <div class="flex justify-end">
                        <button class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Comment
                        </button>
                    </div>
                </form>
            </div>
        @endcan



    </div>
@endsection
<script>
    function toggleEdit(id) {
        document.getElementById(`edit-${id}`).classList.toggle('hidden');
    }
</script>
