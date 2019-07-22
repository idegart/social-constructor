<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schemas', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('script_id');

            $table->string('title');

            $table->timestamps();

            $table->foreign('script_id')->references('id')->on('scripts');
        });

        Schema::table('scripts', function (Blueprint $table) {
            $table->unsignedBigInteger('starter_schema_id')->nullable();

            $table->foreign('starter_schema_id', 'starter_schema')->references('id')->on('schemas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scripts', function (Blueprint $table) {
            $table->dropForeign('starter_schema');
            $table->dropColumn('starter_schema_id');
        });

        Schema::dropIfExists('schemas');
    }
}
