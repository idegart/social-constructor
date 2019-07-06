<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVkontakteMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vkontakte_messages', function (Blueprint $table) {
            $table->integer('id');

            $table->integer('from_id');
            $table->integer('peer_id');

            $table->text('text')->nullable();
            $table->timestamp('date')->nullable();

            $table->json('attachments')->nullable();

            $table->boolean('important')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vkontakte_messages');
    }
}
