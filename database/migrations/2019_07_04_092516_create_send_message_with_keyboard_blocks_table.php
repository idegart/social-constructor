<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendMessageWithKeyboardBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_message_with_keyboard_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('message')->nullable();

            $table->unsignedBigInteger('error_next_block_id')->nullable();

            $table->timestamps();

            $table->foreign('error_next_block_id')
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
        Schema::dropIfExists('send_message_with_keyboard_blocks');
    }
}
