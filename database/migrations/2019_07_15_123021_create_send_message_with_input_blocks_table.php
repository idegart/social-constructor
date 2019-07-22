<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendMessageWithInputBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_message_with_input_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('message')->nullable();

            $table->unsignedBigInteger('param_id')->nullable();

            $table->unsignedBigInteger('next_block_id')->nullable();

            $table->unsignedBigInteger('error_next_block_id')->nullable();

            $table->timestamps();

            $table->foreign('param_id')
                ->references('id')->on('script_variables')
                ->onDelete('set null');

            $table->foreign('next_block_id')
                ->references('id')->on('blocks')
                ->onDelete('set null');

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
        Schema::dropIfExists('send_message_with_input_blocks');
    }
}
