<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_clients', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->morphs('client');

            $table->integer('social_channel_id');

            $table->timestamps();

            $table->foreign('social_channel_id')
                ->references('id')->on('social_channels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_clients');
    }
}
