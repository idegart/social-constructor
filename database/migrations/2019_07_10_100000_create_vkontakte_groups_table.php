<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVkontakteGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vkontakte_groups', function (Blueprint $table) {
            $table->integer('id')->primary();

            $table->string('name');
            $table->string('screen_name');
            $table->boolean('is_closed');
            $table->string('photo_200');

            $table->string('_access_token')->nullable();
            $table->string('_confirmation_code')->nullable();
            $table->string('_server_id')->nullable();

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
        Schema::dropIfExists('vkontakte_groups');
    }
}
