<?php
namespace App\Services;

use App\Models\Comment;
use App\Models\Task;
use App\Repositories\Contracts\CommentRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function __construct(protected CommentRepositoryContract $commentRepository)
    {}

    public function list(Task $task)  {
        return $this->commentRepository->getTaskComment($task);
    }
    public function create(Task $task,array $data){
        $comment=$this->commentRepository->createForTask($task,[
            'body'=>$data['body'],
            'user_id'=>Auth::id(),
            'user_type'=>Auth::user()::class
        ]);
        if(!empty($data['image'])){
            $this->commentRepository->storePhoto($comment, $data['image']);
        }
        return $comment;
    }
    public function update(Comment $comment,array $data){
        $updatedComment = $this->commentRepository->update($comment, [
            'body' => $data['body']
        ]);
        if(!empty($data['image'])){
            $this->commentRepository->replacePhoto($comment, $data['image']);
        }
        return $updatedComment;
    }
    public function delete(Comment $comment){
        return $this->commentRepository->delete($comment);
    }
    public function deleted(Task $task){
        return $this->commentRepository->getDeletedComment($task);
    }
    public function restore(int $id){
        return $this->commentRepository->restore($id);
    }
    public function forceDeleted(int $id)  {
        $comment = $this->commentRepository->findDeleted($id);
        if($comment->photo){
            $this->commentRepository->deletePhoto($comment);
        }
        $this->commentRepository->forceDelete($id);
        return $comment;
    }
}