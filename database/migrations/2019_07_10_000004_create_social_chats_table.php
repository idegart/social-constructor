<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_chats', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('social_channel_id');
            $table->unsignedBigInteger('social_client_id');

            $table->unsignedBigInteger('current_block_id')->nullable();

            $table->timestamps();

            $table->foreign('social_channel_id')
                ->references('id')->on('social_channels');

            $table->foreign('social_client_id')
                ->references('id')->on('social_clients');

            $table->foreign('current_block_id')
                ->references('id')->on('blocks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_chats');
    }
}
