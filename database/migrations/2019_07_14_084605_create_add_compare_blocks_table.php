<?php

use App\Models\Block\Params\CompareParam;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddCompareBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_compare_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('param_first_id')->nullable();

            $table->enum('value_sign', CompareParam::SIGNS)->default(CompareParam::SIGN_EQUAL);

            $table->integer('param_second_id')->nullable();

            $table->integer('value_integer')->nullable();
            $table->date('value_date')->nullable();
            $table->time('value_time')->nullable();

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
        Schema::dropIfExists('add_compare_blocks');
    }
}
