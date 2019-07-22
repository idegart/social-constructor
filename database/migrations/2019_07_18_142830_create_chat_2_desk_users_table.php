<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChat2DeskUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_2_desk_users', function (Blueprint $table) {
            $table->bigInteger('id');

            $table->string('name');
            $table->string('assigned_name')->nullable();

            $table->string('phone')->nullable();
            $table->string('client_phone')->nullable();

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
        Schema::dropIfExists('chat_2_desk_users');
    }
}
