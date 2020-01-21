<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('teacher_id');
            $table->string('sub_desc');
            $table->string('sub_code');
            $table->string('sub_sec');
            $table->string('day');
            $table->string('type');
            $table->string('time');
            $table->string('sy');
            $table->string('semester');
            $table->integer('formula_times');
            $table->integer('formula_plus');
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
         Schema::dropIfExists('class_records');
    }
}
