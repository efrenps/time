<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/* Route::resource('usertables', 'UsertablesController');

Route::resource('employeesattendances', 'EmployeesAttendancesController');

Route::get('index', array('uses' => 'UserTablesController@get_listEmployees'));
*/
// Nuevos

Route::get('/', array('uses' => 'UserTablesController@get_dashboard'));

Route::get('main', array('uses' => 'EmployeesController@get_home'));

Route::get('dashboard', array('uses' => 'UserTablesController@get_dashboard'));

Route::get('getdata', array('uses' => 'UserTablesController@get_listEmployees'));

Route::get('authenticate', array('uses' => 'UserTablesController@post_authenticate'));

Route::get('savework', array('uses' => 'UserTablesController@post_SaveStartWork'));

Route::post('stopwork', array('uses' => 'UserTablesController@post_SaveStopWork'));

Route::get('employeeActions', array('uses' => 'UserTablesController@get_employeeActions'));

Route::get('AttendanceData', array('uses' => 'UserTablesController@post_AttendanceData'));

Route::get('SaveManualWork', array('uses' => 'UserTablesController@post_SaveManual'));
