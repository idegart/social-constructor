<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetParamBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_param_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('param_id')->nullable();

            $table->unsignedBigInteger('value_param_id')->nullable();

            $table->boolean('value_boolean')->nullable();
            $table->string('value_string')->nullable();
            $table->integer('value_integer')->nullable();

            $table->date('value_date')->nullable();
            $table->time('value_time')->nullable();
            $table->dateTime('value_datetime')->nullable();

            $table->unsignedBigInteger('next_block_id')->nullable();

            $table->timestamps();

            $table->foreign('param_id')
                ->references('id')->on('script_variables')
                ->onDelete('set null');

            $table->foreign('value_param_id')
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
        Schema::dropIfExists('set_param_blocks');
    }
}
