<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students',function(Blueprint $table){
            $table->increments('id'); 
            $table->string('stud_num')->unique(); 
             $table->string('password'); 
            $table->string('stud_fname'); 
            $table->string('stud_lname'); 
            $table->string('stud_address'); 
            $table->string('stud_contact_num')->nullable(); 
            $table->integer('course_id'); 
            $table->string('year'); 
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
        Schema::dropIfExists('students');
    }
}
