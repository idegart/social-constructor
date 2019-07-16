<?php

use App\Models\Block\Params\CompareParam;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompareParamBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compare_param_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('param_id')->nullable();

            $table->enum('value_sign', CompareParam::SIGNS)->default(CompareParam::SIGN_EQUAL);

            $table->integer('value_param_id')->nullable();

            $table->string('value_string')->nullable();
            $table->integer('value_integer')->nullable();

            $table->date('value_date')->nullable();
            $table->time('value_time')->nullable();
            $table->dateTime('value_datetime')->nullable();
            $table->enum('date_precision', CompareParam::PRECISIONS)->default(CompareParam::PRECISION_MINUTE);

            $table->integer('true_next_block_id')->nullable();
            $table->integer('false_next_block_id')->nullable();

            $table->timestamps();

            $table->foreign('param_id')
                ->references('id')->on('script_variables')
                ->onDelete('set null');

            $table->foreign('value_param_id')
                ->references('id')->on('script_variables')
                ->onDelete('set null');

            $table->foreign('true_next_block_id')
                ->references('id')->on('blocks')
                ->onDelete('set null');

            $table->foreign('false_next_block_id')
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
        Schema::dropIfExists('compare_param_blocks');
    }
}
