<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamPart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_parts',function(Blueprint $table){
           $table->increments('id');
           $table->string('exam_type');
           $table->string('examination_id');
           $table->string('exam_topic');
           $table->string('exam_instruction');
           $table->integer('part_num');
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
        Schema::dropIfExists('exam_parts');
    }
}
