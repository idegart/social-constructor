<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalApiBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_api_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('external_api_id')->nullable();

            $table->string('handler')->nullable();

            $table->timestamps();

            $table->foreign('external_api_id')
                ->references('id')->on('script_external_api')
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
        Schema::dropIfExists('external_api_blocks');
    }
}
