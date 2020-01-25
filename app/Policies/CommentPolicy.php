<?php

namespace App\Policies;

use App\Comment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function owns_comment(User $user, Comment $comment)
    {
        return (int)$user->id === (int)$comment->user_id;
    }
}
