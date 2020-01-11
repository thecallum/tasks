<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['name', 'order', 'description', 'task_id', 'board_id'];

    public function board() {
        return $this->belongsTo('App\Board')->withDefault();
    }

    public function task() {
        return $this->belongsTo('App\Task')->withDefault();
    }
}
