<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_messages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->morphs('message');

            $table->integer('social_chat_id');
            $table->integer('social_client_id')->nullable();

            $table->timestamps();

            $table->foreign('social_chat_id')
                ->references('id')->on('social_chats');

            $table->foreign('social_client_id')
                ->references('id')->on('social_clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_messages');
    }
}
