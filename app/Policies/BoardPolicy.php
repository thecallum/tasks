<?php

namespace App\Policies;

use App\Board;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BoardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the board.
     *
     * @param  \App\User  $user
     * @param  \App\Board  $board
     * @return mixed
     */

    public function update(User $user, Board $board)
    {
        return (int)$user->id === (int)$board->user_id;
    }

    public function owns_board(User $user, Board $board)
    {
        return (int)$board->user_id === (int)$user->id;
    }
}
