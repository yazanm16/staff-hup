<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Task;
use App\Services\CommentService;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class CommentController extends Controller
{
    use ApiResponse ,AuthorizesRequests;


    public function __construct(protected CommentService $commentService)
    {
        
    }

    public function index(Task $task)
    {
        $this->authorize('viewAny', Comment::class);

        $comments = $this->commentService->list($task);

        return $this->success(
            ['comments'=>CommentResource::collection($comments)->resolve()],
            'Comments list'
        );
    }
    public function store(StoreCommentRequest $request, Task $task)
    {
        $this->authorize('create', Comment::class);
        $comment = $this->commentService->create($task, $request->validated());

        return $this->success(
            ['comment'=>new CommentResource($comment)],
            'Comment created successfully'
        );
    }

    public function update(StoreCommentRequest $request, Comment $comment)
    {   
        $this->authorize('update', $comment);
        $comment = $this->commentService->update($comment, $request->validated());

        return $this->success(
            ['comment'=>new CommentResource($comment)],
            'Comment updated successfully'
        );
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $this->commentService->delete($comment);

        return $this->success([], 'Comment deleted successfully');
    }
    public function deleted(Task $task)
    {
        $this->authorize('viewDeleted', Comment::class);
        $comments = $this->commentService->deleted($task);
        return $this->success(
             ['comments' => CommentResource::collection($comments)->resolve()],
            'Deleted comments'
        );
    }

    public function restore($id)
    {
        $comment = $this->commentService->restore($id);

        return $this->success(
            ['comment'=>new CommentResource($comment)],
            'Comment restored'
        );
    }

    public function forceDelete($id)
    {
        $this->commentService->forceDeleted($id);

        return $this->success([], 'Comment permanently deleted');
    }
}
