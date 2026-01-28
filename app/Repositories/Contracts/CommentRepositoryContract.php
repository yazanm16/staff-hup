<?php
namespace App\Repositories\Contracts;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;

interface CommentRepositoryContract
{
    public function getTaskComment(Task $task): Collection;
    public function createForTask(Task $task, array $data): Comment;
    public function storePhoto(Comment $comment, UploadedFile $image);
    public function replacePhoto(Comment $comment, UploadedFile $image);
    public function deletePhoto(Comment $comment);
    public function update(Comment $comment, array $data): Comment;
    public function delete(Comment $comment): void;
    public function getDeletedComment(Task $task): Collection;
    public function findDeleted(int $id): Comment;
    public function restore(int $id): Comment;
    public function forceDelete(int $id): void;
}