<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnouncements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
      public function up()
    {
         Schema::create('announcements',function(Blueprint $table){
           $table->increments('id');
           $table->longText('announcement');
           $table->string('date');
           $table->string('time');
           $table->string('sy');
           $table->string('semester');
           $table->integer('teacher_id');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcements');
        //
    }
}
