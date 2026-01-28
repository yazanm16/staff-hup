<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Task;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{   
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected CommentService $commentService)
    {
    }
    public function index(Task $task)
    {
        $this->authorize('viewAny', Comment::class);
        $comments = $this->commentService->list($task);
        return view('comment.index',compact('task','comments'));
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Task $task)
    {
        $this->authorize('create', Comment::class);
        $this->commentService->create($task, $request->validated());

        return back()->with('message', 'Comment added successfully.')->with('type','success');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCommentRequest $request,Task $task, Comment $comment)
    {
        $this->authorize('update', $comment);

        $this->commentService->update($comment, $request->validated());

        return back()->with('message', 'Comment updated successfully.')->with('type','success');
    }

    
    public function destroy(Task $task ,Comment $comment)
    {
        $this->authorize('delete', $comment);
        $this->commentService->delete($comment);
        return back()->with('message', 'Comment deleted successfully.')->with('type','success');
    }

    public function deleted(Task $task)
    {
        $this->authorize('viewDeleted', Comment::class);

        $comments = $this->commentService->deleted($task);
        return view('comment.deleted', compact('comments','task'));
    }

    public function restore($id)
    {
        $comment = $this->commentService->restore($id);
        $this->authorize('restore', $comment);
        return back()->with('message', 'Comment restored successfully')->with('type','success');
    }

    public function forceDelete($id)
    {   
        $comment = $this->commentService->forceDeleted($id);
        $this->authorize('forceDelete', $comment);
        return back()->with('message', 'Comment permanently deleted')->with('type','success');
    }


}
