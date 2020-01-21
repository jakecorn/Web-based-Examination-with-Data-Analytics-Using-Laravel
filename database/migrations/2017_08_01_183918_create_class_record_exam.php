<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassRecordExam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_record_exams',function(Blueprint $table){
           $table->increments('id');
           $table->integer('class_record_id');
           $table->integer('examination_id');
           $table->boolean('visibility')->default(false)->nullable();
           $table->boolean('lock_exam')->default(false)->nullable();
           $table->boolean('done_checking');
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
        Schema::dropIfExists('class_record_exams');
    }
}
