<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialFilterBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_filter_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('vkontakte_next_block_id')->nullable();

            $table->unsignedBigInteger('telegram_next_block_id')->nullable();

            $table->unsignedBigInteger('other_next_block_id')->nullable();


            $table->foreign('vkontakte_next_block_id')
                ->references('id')->on('blocks')
                ->onDelete('set null');

            $table->foreign('telegram_next_block_id')
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
        Schema::dropIfExists('social_filter_blocks');
    }
}
