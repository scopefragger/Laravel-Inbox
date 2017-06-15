<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ThreadsParticipantsMessages extends Migration
{
    /**
     * Inserts the additional rows required for authentication
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbox_threads', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->boolean('enabled');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('inbox_messages', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->string('thread_id');
            $table->string('participant_id');
            $table->string('body');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('inbox_participants', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->string('thread_id');
            $table->string('user_id');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('inbox_actions', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->string('message_id');
            $table->string('participant_id');
            $table->string('key');
            $table->string('value');
            $table->softDeletes();
            $table->timestamps();
        });
    }
}
