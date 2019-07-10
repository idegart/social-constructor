<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialChannelClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_channel_clients', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('social_channel_id');

            $table->integer('social_client_id');

            $table->timestamps();

            $table->foreign('social_channel_id')
                ->references('id')->on('social_channels');

            $table->foreign('social_client_id')
                ->references('id')->on('social_clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_channel_clients');
    }
}
