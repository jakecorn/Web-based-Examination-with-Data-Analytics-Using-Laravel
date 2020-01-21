<?php
Route::group(['middleware' => ['web','auth','RestrictAccess'], 'prefix' => 'teacher', 'namespace' => 'Modules\Teacher\Http\Controllers'], function()
{


    Route::get('/setsession', 'TeacherController@index');
    Route::get('/', 'TeacherController@getClassRecordList')->name('getClassRecordList');

    Route::get('/classrecord/', 'TeacherController@getClassRecordList')->name('classrecordlist');
    Route::get('/classrecord/{id}', 'TeacherController@getClassRecord')->name('classrecord');

    Route::get('/classrecord/{id}/delete', 'TeacherController@deleteClassRecord')->name('deleteclassrecord');
    Route::get('/classrecord/{id}/update', 'TeacherController@getClassRecordUpdate')->name('classrecordupdate');
    Route::post('/classrecord/{id}/update', 'TeacherController@storeClassRecordUpdate');

    Route::get('/classrecord/{c_id}/add/{id}', 'TeacherController@addScoreRecord')->name('addscorerecord');
    Route::post('/classrecord/{c_id}/add/{id}', 'TeacherController@storeScoreRecord');
    
    Route::get('/classrecord/new/create', 'TeacherController@getCreate')->name('createclassrecord');
    Route::post('/classrecord/new/create', 'TeacherController@storeClassRecord');
    
    Route::get('/classrecord/{id}/grade', 'TeacherController@getGrade')->name('getgrade');
    Route::get('/classrecord/{id}/grade/print', 'TeacherController@classRecordPrint')->name('classrecordprint');
    Route::get('/classrecord/{id}/grade/print/by_term', 'TeacherController@classRecordPrint_term')->name('classrecordprint2');
    Route::post('/classrecord/updatescore/jake', 'TeacherController@storeUpdateScore');



    //student
    Route::get('/classrecord/{id}/student', 'TeacherController@studentList')->name('studentList');
    Route::get('/classrecord/{id}/student/create', 'TeacherController@getAddStudent')->name('getAddStudent');
    Route::post('/classrecord/{id}/student/create', 'TeacherController@storeStudent');
    Route::get('/classrecord/{id}/student/update/{stud_id}', 'TeacherController@updateStudent')->name('updateStudent');
    Route::post('/classrecord/{id}/student/update/{stud_id}', 'TeacherController@storeUpdateStudent');
    Route::post('/classrecord/student/search', 'TeacherController@searchStudent');
    Route::get('/classrecord/{id}/student/create/upload', 'TeacherController@uploadStudent')->name('uploadStudent');
    Route::post('/classrecord/{id}/student/create/upload', 'TeacherController@storeUploadStudent');
    Route::get('/classrecord/student/delete/{stud_id}', 'TeacherController@deleteStudent')->name('deleteStudent');

    // examination
    Route::get('/classrecord/{id}/exam', 'TeacherController@classRecordExam')->name('exam');
    Route::get('/{id}/check/{type}', 'TeacherController@checkExam')->name('checkexam');
    Route::post('/{id}/check/{type}', 'TeacherController@storeStudentAnswer');
    Route::post('/classrecord/exam/lock', 'TeacherController@lockExam');
    Route::post('/classrecord/exam/pause', 'TeacherController@pause');
    Route::post('/examination/check/score/save', 'TeacherController@savePoints');
    Route::post('/classrecord/exam/visibility', 'TeacherController@updateExaminationVisibility');

    // item analysis
    Route::get('/data-analytics/analysis', 'TeacherController@dataAnalytics')->name('dataAnalytics');   
    Route::get('/data-analytics/analysis/class/{class}', 'TeacherController@itemAnalysis')->name('itemAnalysis');
    Route::get('/data-analytics/analysis/class/{class}/{All}', 'TeacherController@itemAnalysis')->name('itemAnalysis');
    Route::get('/data-analytics/analysis/class/{class}/print/{rtype}', 'TeacherController@analysisResultPrint')->name('analysisData');

    // statistics
    Route::get('/data-analytics/statistics', 'TeacherController@itemStatisticsIndex')->name('itemStatisticsIndex');
    Route::get('/data-analytics/statistics/id/{id}', 'TeacherController@itemStatistics')->name('itemStatistics');
    
    Route::get('/data-analytics/statistics/id/{id}/print/graph', 'TeacherController@statisticsGraphPrint')->name('statisticsGraphPrint');
    Route::get('/data-analytics/statistics/id/{id}/print/test-paper', 'TeacherController@statisticsTestPaperPrint')->name('statisticsTestPaperPrint');

    Route::get('/myaccount', 'TeacherController@myAccount');
    Route::post('/myaccount', 'TeacherController@updateAccount');
    Route::get('/tester', function(){
        $start_date = new DateTime("2019-09-04 13:15:37");
        $end_date = new DateTime("2019-09-04 13:15:37");
        $timediff = $start_date->diff($end_date);
        $minutes = ($timediff->days * 24 * 60) + ($timediff->h * 60) + $timediff->i;
        // $start_date = strtotime("");

        // $end_date = strtotime("");
        var_dump($minutes);
    });
 
});
