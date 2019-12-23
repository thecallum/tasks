<?php

namespace App\Policies;

use App\Task;
use App\User;
use App\Board;

use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;
   
     /**
     * Determine whether the user can view the project.
     *
     * @param  \App\User  $user
     * @param  \App\Task  $task
     * @return mixed
     */

    public function owns_task(User $user, Task $task)
    {
        return $task->board->user_id === $user->id;
    }
  
}
