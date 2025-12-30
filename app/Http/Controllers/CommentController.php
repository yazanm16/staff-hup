<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{   
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Task $task)
    {
        $this->authorize('viewAny', Comment::class);
        $comments = $task->comments()->with(['user.photo', 'photo'])->latest()->get();
        return view('comment.index',compact('task','comments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Task $task)
    {
        $this->authorize('create', Comment::class);
        $comment=$task->comments()->create([
            'body' => $request->body,
            'user_id' => Auth::id(),
            'user_type' => Auth::user()::class,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('comments', 'public');
            $comment->photo()->create([
                'path' => $path,
                'disk' => 'public',
            ]);
        }

        return back()->with('message', 'Comment added successfully.')->with('type','success');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Task $task, Comment $comment)
    {
        $this->authorize('update', $comment);

        if ($comment->task_id !== $task->id) {
            abort(404);
        }

        $request->validate([
            'body' => 'required|string',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        return back()->with('message', 'Comment updated successfully.')->with('type','success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task ,Comment $comment)
    {
        $this->authorize('delete', $comment);
         if ($comment->task_id !== $task->id) {
            abort(404);
        }
        $comment->delete();
        return back()->with('message', 'Comment deleted successfully.')->with('type','success');
    }

    public function deleted(Task $task)
    {
        $this->authorize('viewDeleted', Comment::class);
        
        $query = Comment::onlyTrashed()
            ->with('user')
            ->latest();

        if (! Auth::user()->hasRole('admin')) {
        $query->where('user_id', Auth::id());
    }
        $comments = $query->get();
        return view('comment.deleted', compact('comments','task'));
    }

    public function restore($id)
    {
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $comment);
        $comment->restore();
        return back()->with('message', 'Comment restored successfully')->with('type','success');
    }

    public function forceDelete($id)
    {   
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $comment);
        if($comment->photo){
            $photo = $comment->photo;
            Storage::disk($photo->disk)->delete($photo->path);
            $photo->delete();
        }
        $comment->forceDelete();
        return back()->with('message', 'Comment permanently deleted')->with('type','success');
    }


}
