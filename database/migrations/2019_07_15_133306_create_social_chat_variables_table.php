<?php

use App\Models\Script\ScriptVariable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialChatVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_chat_variables', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('script_id');
            $table->integer('script_variable_id');

            $table->integer('social_chat_id');
            $table->enum('type', ScriptVariable::TYPES)->default(ScriptVariable::TYPE_STRING);

            $table->string('string')->nullable();
            $table->integer('integer')->nullable();
            $table->boolean('boolean')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->dateTime('datetime')->nullable();

            $table->timestamps();

            $table->foreign('script_id')
                ->references('id')->on('scripts');

            $table->foreign('script_variable_id')
                ->references('id')->on('script_variables');

            $table->foreign('social_chat_id')
                ->references('id')->on('social_chats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_chat_variables');
    }
}
