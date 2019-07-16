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

            $table->integer('param_first_id')->nullable();

            $table->enum('value_sign', AddParam::SIGNS)->default(AddParam::SIGN_ADD);

            $table->integer('param_second_id')->nullable();

            $table->integer('value_integer')->nullable();
            $table->integer('value_days')->nullable();
            $table->integer('value_hours')->nullable();
            $table->integer('value_minutes')->nullable();

            $table->integer('next_block_id')->nullable();

            $table->timestamps();

            $table->foreign('param_first_id')
                ->references('id')->on('script_variables')
                ->onDelete('set null');

            $table->foreign('param_second_id')
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
