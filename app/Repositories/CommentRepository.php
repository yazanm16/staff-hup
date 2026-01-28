<?php
namespace App\Repositories;

use App\Models\Comment;
use App\Models\Task;
use App\Repositories\Contracts\CommentRepositoryContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class CommentRepository implements CommentRepositoryContract
{
    public function getTaskComment(Task $task): Collection
    {
        return $task->comments()
            ->with(['user.photo', 'photo'])
            ->latest()->get();
    }
    public function createForTask(Task $task, array $data): Comment
    {
        return $task->comments()->create($data);
    }
    public function deletePhoto(Comment $comment)
    {
        if (!$comment->photo) {
            return;
        }
        Storage::disk('public')->delete($comment->photo->path);
        $comment->photo->delete();
    }
    public function storePhoto(Comment $comment,UploadedFile $image){
        $path=$image->store('comments','public');
        $comment->photo()->create([
            'path' => $path,
            'disk' => 'public'
        ]);
    }
    public function replacePhoto(Comment $comment, UploadedFile $image): void
    {
        $this->deletePhoto($comment);
        $this->storePhoto($comment, $image);
    }
    public function update(Comment $comment, array $data): Comment
    {
        $comment->update($data);
        return $comment->refresh();
    }
    public function delete(Comment $comment): void
    {
        $comment->delete();
    }

    public function getDeletedComment(Task $task): Collection
    {
        return $task->comments()
            ->onlyTrashed()
            ->with('user')
            ->latest()->get();
    }
    public function findDeleted(int $id): Comment
    {
        return Comment::onlyTrashed()->findOrFail($id);
    }
     public function restore(int $id): Comment
    {
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $comment->restore();

        return $comment;
    }

    public function forceDelete(int $id): void
    {
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $comment->forceDelete();
    }
    
}