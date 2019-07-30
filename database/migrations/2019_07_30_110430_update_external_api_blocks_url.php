<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateExternalApiBlocksUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_api_blocks', function (Blueprint $table) {

            $table->dropColumn('url');
            $table->dropColumn('auth_login');
            $table->dropColumn('auth_password');

            $table->unsignedBigInteger('external_api_id')->nullable();

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
        Schema::table('external_api_blocks', function (Blueprint $table) {
            $table->dropColumn('external_api_id');

            $table->string('url')->nullable();

            $table->string('auth_login')->nullable();
            $table->string('auth_password')->nullable();
        });
    }
}
