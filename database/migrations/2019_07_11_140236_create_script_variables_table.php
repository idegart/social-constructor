<?php

use App\Models\Script\ScriptVariable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScriptVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('script_variables', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('script_id');

            $table->string('variable');

            $table->enum('type', ScriptVariable::TYPES)->default(ScriptVariable::TYPE_STRING);

            $table->string('validation')->nullable();

            $table->string('default_string')->nullable();
            $table->integer('default_integer')->nullable();
            $table->boolean('default_boolean')->nullable();
            $table->date('default_date')->nullable();
            $table->time('default_time')->nullable();
            $table->dateTime('default_datetime')->nullable();

            $table->timestamps();

            $table->foreign('script_id')
                ->references('id')->on('scripts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('script_variables');
    }
}
