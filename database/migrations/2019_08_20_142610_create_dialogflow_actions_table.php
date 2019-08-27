<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDialogflowActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialogflow_actions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('dialogflow_block_id');

            $table->string('action');

            $table->unsignedBigInteger('next_block_id')->nullable();

            $table->timestamps();

            $table->foreign('dialogflow_block_id')
                ->references('id')->on('dialogflow_blocks')
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
        Schema::dropIfExists('dialogflow_actions');
    }
}
