<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
   public function viewAny(User $user): bool
    {
        return $user->can('task.view');
    }
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        return $user->can('task.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('comment.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id && $user->can('comment.update-own');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        if($user->hasRole('admin')){
            return true;
        }
        return $user->id === $comment->user_id && $user->can('comment.delete-own');
    }


    public function viewDeleted(User $user) : bool {
        return $user->can('comment.view-deleted');
    }

    /**
     * Determine whether the user can restore the model.
     */

    public function restore(User $user, Comment $comment): bool
    {
        return $user->can('comment.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->can('comment.force-delete');
    }
}
