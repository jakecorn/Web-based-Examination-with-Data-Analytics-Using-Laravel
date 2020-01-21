<?php

use Illuminate\Database\Seeder;

class AdminAccount extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->insert([
        	'name'=> "Admin",
        	'username'=> "admin1",
        	'password'=> bcrypt("admin1"),
            'role'=> "Admin",
        	'status'=> 1,

        	]);
    }
}
