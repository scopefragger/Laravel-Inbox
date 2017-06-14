<?php

namespace Scopefragger\LaravelInbox\Models;

use Illuminate\Database\Eloquent\Model;

class Actions extends Model
{
    protected $table = 'inbox_actions';
    public $timestamps = true;
}