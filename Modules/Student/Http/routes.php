<?php

Route::group(['middleware' => ['web','auth','StudentAccess'], 'prefix' => 'student', 'namespace' => 'Modules\Student\Http\Controllers'], function()
{
    Route::get('/session', 'StudentController@index');

    Route::get('/', 'StudentController@subjectList')->name('subjectlist');
    Route::get('/{id}/exam', 'StudentController@examList')->name('examlist');

    Route::get('/subject/{id}/record/', 'StudentController@viewRecord')->name('viewrecord');

    Route::get('/exam/{class_record_id}/{id}/start/', 'StudentController@startTake')->name('start');

    Route::get('/exam/{class_record_id}/{id}/start/part/{part_num}/position/{pos}', 'StudentController@examinationStart')->name('startexam');

    Route::post('/exam/{class_record_id}/{id}/start/part/{part_num}/position/{pos}', 'StudentController@examNext')->name('examnext');

    Route::get('/exam/{id}/score', 'StudentController@examScore')->name('score');
    Route::get('/exam/{id}/submitscore', 'StudentController@storeExamScore')->name('submitscore');
    Route::get('/exam/{id}/viewanswer', 'StudentController@viewAnswer')->name('viewanswer');

 

    Route::get('/home', 'StudentController@home');

    // announcement
    Route::get('/announcement', 'StudentController@announcementList')->name('announcement');

    // files
    Route::get('/files', 'StudentController@filesList')->name('filesList');

    //settings 
    Route::get('/settings', 'StudentController@settings')->name('settings');
    Route::post('/settings', 'StudentController@saveSettings');


    //settings 
    Route::get('/myaccount', 'StudentController@account')->name('account');
    Route::post('/myaccount', 'StudentController@storeAccount');
});
