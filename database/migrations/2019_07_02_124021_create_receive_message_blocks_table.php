<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiveMessageBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receive_message_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('message')->nullable();

            $table->integer('next_block')->nullable();

            $table->timestamps();

            $table->foreign('next_block')
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
        Schema::dropIfExists('receive_message_blocks');
    }
}
