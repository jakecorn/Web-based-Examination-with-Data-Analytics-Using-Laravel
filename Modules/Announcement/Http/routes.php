<?php

Route::group(['middleware' => ['web','auth','RestrictAccess'], 'prefix' => 'teacher/announcement', 'namespace' => 'Modules\Announcement\Http\Controllers'], function()
{
    Route::get('/', 'AnnouncementController@index')->name('announcementlist');

    Route::get('/create', 'AnnouncementController@create')->name('createAnnouncement');
    Route::post('/create', 'AnnouncementController@storeCreate');
    
    Route::get('/delete/{id}', 'AnnouncementController@deleteAnnouncement')->name('deleteAnnouncement');
    Route::get('/update/{id}', 'AnnouncementController@updateAnnouncement')->name('updateAnnouncement');
    Route::post('/update/{id}', 'AnnouncementController@storeupdateAnnouncement');
});

Route::group(['middleware' => ['web','auth','RestrictAccess'], 'prefix' => 'teacher/file', 'namespace' => 'Modules\Announcement\Http\Controllers'], function()
{
    Route::get('/', 'FileController@fileList')->name('fileList');

    Route::get('/create', 'FileController@createFile')->name('createFile');
    Route::post('/create', 'FileController@storeCreateFile');
    
    Route::get('/delete/{id}', 'FileController@deleteFile')->name('deleteFile');
    Route::get('/update/{id}', 'FileController@updateFile')->name('updateFile');
    Route::post('/update/{id}', 'FileController@storeUpdateFile');
});
