<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentFiles extends Migration
{
    public function up()
    {
         Schema::create('student_files',function(Blueprint $table){
           $table->increments('id');
           $table->integer('file_id');
           $table->integer('student_id');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_files');
    }
}
