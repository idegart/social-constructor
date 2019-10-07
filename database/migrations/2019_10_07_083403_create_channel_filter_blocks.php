<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelFilterBlocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_filter_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('channels')->nullable();

            $table->unsignedBigInteger('channels_next_block_id')->nullable();

            $table->unsignedBigInteger('other_next_block_id')->nullable();

            $table->foreign('channels_next_block_id')
                ->references('id')->on('blocks')
                ->onDelete('set null');

            $table->foreign('other_next_block_id')
                ->references('id')->on('blocks')
                ->onDelete('set null');

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
        Schema::dropIfExists('channel_filter_blocks');
    }
}
