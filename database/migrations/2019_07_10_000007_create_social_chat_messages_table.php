<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_chat_messages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('social_chat_id');
            $table->unsignedBigInteger('social_message_id');

            $table->timestamps();

            $table->foreign('social_chat_id')
                ->references('id')->on('social_chats');

            $table->foreign('social_message_id')
                ->references('id')->on('social_messages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_chat_messages');
    }
}
