<?php

use Illuminate\Database\Seeder;

class Course extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table("courses")->insert([
        	'course_code'=> "BSINT",
        	'course_desc'=> "Bachelor of Science in Information Technology",

        	]);

         DB::table("courses")->insert([
        	'course_code'=> "BSCS",
        	'course_desc'=> "Bachelor of Science in Computer Science ",

        	]);

         DB::table("courses")->insert([
        	'course_code'=> "BSED",
        	'course_desc'=> "Bachelor of Secodary Education",

        	]);

         DB::table("courses")->insert([
        	'course_code'=> "BEED",
        	'course_desc'=> "Bachelor of Elementary Education",

        	]);
    }
}
