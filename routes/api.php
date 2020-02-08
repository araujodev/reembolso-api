<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
});

Route::group(['middleware' => 'api'], function ($router) {
    Route::get('/refunds', 'RefundController@all');
    Route::get('/employees/{employee_id}/refunds', 'RefundController@index');
    Route::post('/employees/{employee_id}/refunds/{refund_id}/approve', 'RefundController@approve');
    Route::post('/employees/{employee_id}/refunds', 'RefundController@store');
    Route::put('/employees/{employee_id}/refunds/{refund_id}', 'RefundController@update');
    Route::get('/employees/{employee_id}/refunds/{refund_id}', 'RefundController@show');
    Route::delete('/employees/{employee_id}/refunds/{refund_id}', 'RefundController@destroy');

    Route::apiResource('employees', 'EmployeeController');
});
