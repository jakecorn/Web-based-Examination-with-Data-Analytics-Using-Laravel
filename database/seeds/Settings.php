<?php

use Illuminate\Database\Seeder;

class Settings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $year = date("Y");
       $year2 = date("Y")+1;
        DB::table("settings")->insert([
    	'sy'=> "$year-$year2",
    	'semester'=> "First",
    	'term'=> "Midterm",
    	]);
    }
}
