<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
 


Auth::routes();

Route::get('/', 'Auth\LoginController@showLoginForm')->name("login");
Route::post('/', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name("logout");

Route::get('/home',function(){
 	return redirect("teacher");
});


 // Route::get('register', 'Auth\RegisterController@showRegistrationForm');
 // Route::post('register', 'Auth\RegisterController@register');

 