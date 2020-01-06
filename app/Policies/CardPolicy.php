<?php

namespace App\Policies;

use App\Card;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CardPolicy
{
    use HandlesAuthorization;

    public function owns_card(User $user, Card $card)
    {
        return (int)$card->board->user_id === (int)$user->id;
    }
    
}
