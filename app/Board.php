<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Task;
use App\Card;

class Board extends Model
{
    protected $fillable = ['name', 'user_id'];

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function cards() {
        // return true;

        return $this->hasMany(Card::class);
    }
}
