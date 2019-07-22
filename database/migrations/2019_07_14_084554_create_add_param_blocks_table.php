<?php

use App\Models\Block\Params\AddParam;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddParamBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_param_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('param_id')->nullable();

            $table->enum('value_sign', AddParam::SIGNS)->default(AddParam::SIGN_ADD);

            $table->unsignedBigInteger('value_param_id')->nullable();

            $table->integer('value_integer')->nullable();
            $table->integer('value_days')->nullable();
            $table->integer('value_hours')->nullable();
            $table->integer('value_minutes')->nullable();

            $table->unsignedBigInteger('next_block_id')->nullable();

            $table->timestamps();

            $table->foreign('param_id')
                ->references('id')->on('script_variables')
                ->onDelete('set null');

            $table->foreign('value_param_id')
                ->references('id')->on('script_variables')
                ->onDelete('set null');

            $table->foreign('next_block_id')
                ->references('id')->on('blocks')
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
        Schema::dropIfExists('add_param_blocks');
    }
}
