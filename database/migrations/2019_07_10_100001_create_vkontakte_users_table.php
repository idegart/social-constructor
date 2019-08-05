<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVkontakteUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vkontakte_users', function (Blueprint $table) {
            $table->bigIncrements('_id');

            $table->bigInteger('id');

            $table->string('first_name');
            $table->string('last_name');
            $table->boolean('is_closed');

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
        Schema::dropIfExists('vkontakte_users');
    }
}
