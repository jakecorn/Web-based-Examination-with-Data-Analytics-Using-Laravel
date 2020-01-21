<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChoicesRand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
      public function up()
    {
         Schema::create('rand_choices',function(Blueprint $table){
           $table->increments('id');
           $table->integer('student_id');
           $table->integer('question_id');
           $table->integer('choice_id');           
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rand_choices');
        //
    }
}
