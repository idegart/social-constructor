<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendMessageWithKeyboardBlockButtonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_message_with_keyboard_block_buttons', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('send_message_with_keyboard_block_id');

            $table->string('label');

            $table->integer('next_block')->nullable();

            $table->timestamps();

            $table->foreign('send_message_with_keyboard_block_id')
                ->references('id')->on('send_message_with_keyboard_blocks')
                ->onDelete('cascade');

            $table->foreign('next_block')
                ->references('id')->on('blocks')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('send_message_with_keyboard_block_buttons');
    }
}
