<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScriptExternalApiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('script_external_api', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('script_id');

            $table->string('title');
            $table->string('url');

            $table->string('secret');

            $table->string('auth_login')->nullable();
            $table->string('auth_password')->nullable();

            $table->timestamps();

            $table->foreign('script_id')
                ->references('id')->on('scripts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('script_external_api');
    }
}
