<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserUpdateField2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropColumn('is_registered');
        });
        Schema::table('users',function(Blueprint $table){
            $table->enum("is_registered", ['Not registered', 'Done manual registration and present in the masterlist', 'Done manual registration but not found in the masterlist'])->default('Not registered');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
