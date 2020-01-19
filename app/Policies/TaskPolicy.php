<?php

namespace App\Policies;

use App\Task;
use App\User;
use App\Board;

use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function owns_task(User $user, Task $task)
    {
        return (int)$task->board->user_id === (int)$user->id;
    }
  
}
