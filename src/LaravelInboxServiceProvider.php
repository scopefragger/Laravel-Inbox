<?php

namespace Scopefragger\LaravelInbox;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Scopefragger\LaravelInbox\Models\Messages;

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
            $threadID = $this->create();
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

    /**
     * Adds new participant to thread
     *
     * @param null $threadID
     * @param $participant
     */
    public function addParticipants($threadID, $participants)
    {

    }

    /**
     *
     */
    public function read()
    {

    }

    /**
     *
     */
    public function markRead()
    {

    }

    /**
     *
     */
    public function markUnread()
    {

    }

    /**
     *
     */
    public function deleteMessage()
    {

    }

    /**
     *
     */
    public function deleteThread()
    {

    }

    /**
     *
     */
    public function create()
    {

    }


}
