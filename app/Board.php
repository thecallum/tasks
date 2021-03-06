<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Task;
use App\Card;
use App\Comment;

class Board extends Model
{
    protected $fillable = ['name', 'user_id'];

    public function tasks() 
    {
        return $this->hasMany(Task::class);
    }

    public function cards() 
    {
        return $this->hasMany(Card::class);
    }

    public function findCardAtPosition($order) 
    {
        return $this->tasks->where('order', '=', $order)->first();
    }

    public function comments()
    {
        return $this->hasOneThrough('App\Comment', 'App\Card');
    }
}
