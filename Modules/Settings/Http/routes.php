<?php

Route::group(['middleware' => ['web','auth','RestrictAccess'], 'prefix' => 'teacher/settings', 'namespace' => 'Modules\Settings\Http\Controllers'], function()
{
    Route::get('/', 'SettingsController@index')->name('settingsIndex');
    Route::post('/', 'SettingsController@saveSettings');
});
