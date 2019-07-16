<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResetParamBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reset_param_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->boolean('reset_all')->default(false);

            $table->integer('param_id')->nullable();

            $table->integer('next_block_id')->nullable();

            $table->timestamps();

            $table->foreign('param_id')
                ->references('id')->on('script_variables')
                ->onDelete('set null');

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
        Schema::dropIfExists('reset_param_blocks');
    }
}
