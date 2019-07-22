<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChat2DeskMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_2_desk_messages', function (Blueprint $table) {
            $table->bigInteger('id');

            $table->string('type');

            $table->string('text');

            $table->string('transport');

            $table->integer('client_id');

            $table->integer('channel_id')->nullable();

            $table->integer('operator_id')->nullable();


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
        Schema::dropIfExists('chat_2_desk_messages');
    }
}
