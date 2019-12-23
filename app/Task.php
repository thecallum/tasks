<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Card;

class Task extends Model
{
    protected $fillable = ['name', 'board_id', 'order'];

    public function board() {
        return $this->belongsTo('App\Board')->withDefault();
    }

    public function cards() {
        return $this->hasMany(Card::class);
    }
}
