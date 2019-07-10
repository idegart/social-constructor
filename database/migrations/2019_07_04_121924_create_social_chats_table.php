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

            $table->integer('social_channel_id');
            $table->integer('social_client_id');

            $table->integer('current_script_id')->nullable();
            $table->integer('current_schema_id')->nullable();
            $table->integer('current_block_id')->nullable();

            $table->timestamps();

            $table->foreign('social_channel_id')
                ->references('id')->on('social_channels');
            $table->foreign('social_client_id')
                ->references('id')->on('social_clients');

            $table->foreign('current_script_id')
                ->references('id')->on('scripts');
            $table->foreign('current_schema_id')
                ->references('id')->on('schemas');
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
