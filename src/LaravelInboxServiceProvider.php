<?php

namespace Scopefragger\LaravelInbox;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Scopefragger\LaravelInbox\Models\Messages;
use Scopefragger\LaravelInbox\Models\Participants;
use Scopefragger\LaravelInbox\Models\Threads;

class LaravelInboxServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
        $this->publishes([
            __DIR__ . '/Config/config.php' => config_path('laravel-inbox.php'),
        ], 'laravel-inbox');
    }

    /**
     * Sends a single message
     *
     * Sends a message to an existing thread_id,  if there is no $threadID provided
     * a new thread_id will be created.  a call back function can be defined to run
     * trigger events,  such as email notifications.
     *
     * @param string $fromParticipant
     * @param string $threadID
     * @param array $toParticipants
     * @param string $body
     */
    public function send($fromParticipant = '', $threadID = '', $toParticipants = [], $body = '')
    {

        /** Create a new thread if none is provided */
        if (empty($threadID)) {
            $threadID = $this->create($fromParticipant, $toParticipants);
        }

        /** Adds new participants to the conversation */
        if (!empty($toParticipants)) {
            $this->addParticipants($threadID, $toParticipants);
        }

        $message = new Messages();
        $message->thread_id = $threadID;
        $message->body = $body;
        $message->participant_id = $fromParticipant;
        $message->save();

    }

    /**ss
     * Adds new participant to thread
     *
     * @param null $threadID
     * @param $participant
     */
    public function addParticipants($threadID, $participants)
    {
        return Participant::firstOrCreate(['thread_id', $threadID, 'user_id' => $participants]);
    }

    public function getParticipants($threadID)
    {
        return Participants::where('thread_id', $threadID)
    }

    public function getMessageThread($threadID)
    {
        return Message::where('thread_id', $threadID)->get();
    }

    public function getMessage($message_id)
    {
        return Message::where('id', $message_id)->first();
    }

    public function getThread($thread_id)
    {
        return Threads::where('id', $thread_id)->first();
    }

    /**
     * Marks Message read
     *
     * Marks the massage provided i param 1 as read for all participants
     * in provided
     */
    public function markRead($message_id, $participants = [])
    {
        return $this->setValue($message_id, $participants, 'read', true);
    }

    /**
     * Marks Message unread
     *
     * Marks the massage provided i param 1 as unread for all participants
     * in provided
     */
    public function markUnread($message_id, $participants)
    {
        return $this->setValue($message_id, $participants, 'read', false);
    }


    public function setAction($message_id, $participants = [], $key, $value)
    {
        if (!empty($participants)) {
            foreach ($participants as $row) {
                $action = Actions::firstOrCreate(['message_id' => $message_id, 'participant_id' => $row, 'key' => 'read'])->first();
                $action->value = $value;
                $action->save();
            }
        }
    }

    /**
     * Soft deletes message
     */
    public function deleteMessage($message_id)
    {
        return Messages::where('id', $message_id)->delete();
    }

    /**
     * Soft Deletes thread
     */
    public function deleteThread($thread_id)
    {
        return Threads::where('id', $thread_id)->delete();
    }

    /**
     *
     */
    public function create($fromParticipant, $toParticipants)
    {
        /** Create the thread */
        $thread = new Threads();
        $thread->enabled = true;
        $thread->save();

        /** Add all participants */
        $toParticipants[] = $fromParticipant;
        $this->addParticipants($thread->id, $toParticipants);

        /** return the ID */
        return $thread->id;
    }


}
