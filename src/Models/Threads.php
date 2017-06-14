<?php

namespace Scopefragger\LaravelInbox\Models;

use Illuminate\Database\Eloquent\Model;

class Threads extends Model
{
    protected $table = 'inbox_threads';
    public $timestamps = true;

    public function Messages()
    {
        return $this->hasMany('Messages', 'thread_id');
    }

    public function Participants()
    {
        return $this->hasMany('Participants', 'thread_id');
    }

    public function byUser($userID)
    {
        return $this->where('user_id', $userID)->get();
    }
}
