<?php

namespace Scopefragger\LaravelInbox\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $table = 'inbox_messages';
    public $timestamps = true;

    public function Thread()
    {
        return $this->hasOne('Threads', 'thread_id');
    }

    /**
     * Gets all Messages related to a single thread_id
     *
     * @param $threadID
     * @param bool $unreadOnly
     * @param int $limit
     * @return mixed
     */
    public function byThread($threadID, $unreadOnly = false, $limit = 20)
    {
        $query = $this->where('thread_id', $threadID);
        if ($unreadOnly == true) {
            $query = $query->where('read', 0);
        }
        $query = $query->limit($limit);
        return $query->get();
    }


}