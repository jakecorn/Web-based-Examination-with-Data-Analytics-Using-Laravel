<?php

Route::group(['middleware' => ['web','auth','RestrictAccess'], 'prefix' => 'teacher/examination', 'namespace' => 'Modules\Examination\Http\Controllers'], function()
{
    Route::get('/', 'ExaminationController@index')->name('index');
    Route::get('/create', 'ExaminationController@createExam')->name('createexam');
    Route::post('/create', 'ExaminationController@storeCreateExam');


    Route::get('/{id}/delete', 'ExaminationController@deleteExam')->name("deleteexam");

    
    // transfered to cteacher controler
    // Route::post('/{id}/check', 'ExaminationController@storeStudentAnswer')->name('storestudentanswer');

   

    Route::get('/{id}/update', 'ExaminationController@updateExam')->name('updateexam');
    Route::post('/{id}/update', 'ExaminationController@storeUpdateExam');


    Route::get('/{id}/show', 'ExaminationController@showExam')->name('showexam');

    Route::get('/{id}/{p_id}/addquestion/load', 'ExaminationController@addQuestionLoad')->name('addQuestionLoad');
    Route::post('/{id}/{p_id}/addquestion/load', 'ExaminationController@storeAddQuestionLoad');
    Route::get('/{id}/{p_id}/addquestion', 'ExaminationController@addQuestion')->name('addquestion');
    Route::post('/{id}/{p_id}/addquestion', 'ExaminationController@storeAddQuestion');

    // upload question 
    Route::get('/{id}/{p_id}/upload-question', 'ExaminationController@uploadAddQuestion')->name('uploadAddQuestion');
    Route::post('/{id}/{p_id}/upload-question', 'ExaminationController@storeUploadAddQuestion');


    Route::get('/{id}/preview', 'ExaminationController@preview')->name('preview');

    Route::get('/{e_id}/{p_id}/{q_id}/edit', 'ExaminationController@editQuestion')->name('editquestion');
    Route::post('/{e_id}/{p_id}/{q_id}/edit', 'ExaminationController@storeUpdateQuestion');
    
    Route::post('/{e_id}/visibility', 'ExaminationController@updateAnswerVisibility')->name('visibility');

    Route::get('/{e_id}/{p_id}/{q_id}/delete', 'ExaminationController@deleteQuestion')->name('deletequestion');
});
