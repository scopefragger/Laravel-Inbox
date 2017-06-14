<?php

namespace Scopefragger\LaravelInbox\Models;

use Illuminate\Database\Eloquent\Model;

class Participants extends Model
{
    protected $table = 'inbox_participants';
    public $timestamps = true;
}