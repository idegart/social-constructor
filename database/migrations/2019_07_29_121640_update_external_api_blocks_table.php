<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateExternalApiBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_api_blocks', function (Blueprint $table) {
            $table->string('auth_login')->nullable();
            $table->string('auth_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_api_blocks', function (Blueprint $table) {
            $table->dropColumn('auth_login');
            $table->dropColumn('auth_password');
        });
    }
}
