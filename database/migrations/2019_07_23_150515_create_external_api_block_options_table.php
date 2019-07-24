<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalApiBlockOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_api_block_options', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('external_api_blocks_id');

            $table->string('key');

            $table->unsignedBigInteger('next_block_id')->nullable();

            $table->timestamps();

            $table->foreign('external_api_blocks_id')
                ->references('id')->on('external_api_blocks')
                ->onDelete('cascade');

            $table->foreign('next_block_id')
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
        Schema::dropIfExists('external_api_block_options');
    }
}
