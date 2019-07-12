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

            $table->integer('script_id');

            $table->string('variable');

            $table->enum('type', ScriptVariable::TYPES)->default(ScriptVariable::TYPE_STRING);

            $table->string('validation')->nullable();

            $table->string('default')->nullable();

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
        Schema::dropIfExists('script_variables');
    }
}
