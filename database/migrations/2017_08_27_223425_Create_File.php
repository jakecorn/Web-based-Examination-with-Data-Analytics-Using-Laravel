<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
         Schema::create('files',function(Blueprint $table){
           $table->increments('id');
           $table->string('file_name');
           $table->string('rand_name');
           $table->string('description');
           $table->string('file_type');
           $table->string('date');
           $table->string('time');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
        //
    }
}
