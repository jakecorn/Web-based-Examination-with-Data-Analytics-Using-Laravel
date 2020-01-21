<?php

Route::group(['middleware' => ['web','auth','AdminRestriction'], 'prefix' => 'admin', 'namespace' => 'Modules\Admin\Http\Controllers'], function()
{
    Route::get('/', 'AdminController@index');
    Route::get('/user-list/{last_id?}', 'AdminController@index')->name("user_list");
    Route::get('/user-list/{last_id}/{all}', 'AdminController@index')->name("master_list");

    Route::post('/user/change-status', 'AdminController@storeStatus');
    Route::post('/user/password-reset', 'AdminController@passwordReset');
    Route::get('/user/upload-user', 'AdminController@uploadUser')->name("uploaduser");
    Route::post('/user/upload-user', 'AdminController@saveUploadUser');
    Route::post('/user/search', 'AdminController@searchUser');

    // logs
    Route::get('/log/list', 'AdminController@logList')->name('logs');
    Route::post('/log/search', 'AdminController@searchLog');

    // course
    Route::get('/course/create', 'AdminController@courseCreate')->name('courseCreate');
    Route::post('/course/create', 'AdminController@storeCourse');

    Route::get('/course/list', 'AdminController@courseList')->name('courseList');
    Route::get('/course/delete/{id}', 'AdminController@courseDelete')->name('courseDelete');

    Route::get('/course/edit/{id}', 'AdminController@courseEdit')->name('courseEdit');
    Route::post('/course/edit/{id}', 'AdminController@storeCourseEdit');

    Route::get('/system/settings', 'AdminController@settings')->name('settings');
    Route::post('/system/settings', 'AdminController@storeSettings');


    Route::get('/myaccount', 'AdminController@account')->name('account');
    Route::post('/myaccount', 'AdminController@storeAccount');
    Route::get('/settings/update', 'AdminController@settngs')->name("admin-settings");
    Route::post('/settings/update', 'AdminController@save_settngs');
});
