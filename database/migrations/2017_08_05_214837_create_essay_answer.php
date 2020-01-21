<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEssayAnswer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
      public function up()
    {
         Schema::create('long_answers',function(Blueprint $table){
           $table->increments('id');
           $table->integer('student_id');
           $table->integer('question_id');
           $table->longText('answer');           
           $table->string('score')->nullable();
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('long_answers');
        //
    }
}
