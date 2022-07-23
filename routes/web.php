<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'web'], function(){

    Route::get('/login','App\Http\Controllers\Auth\AuthController@login')->name('login');

    Route::post('/login','App\Http\Controllers\Auth\AuthController@process_login')->name('process-login');

    Route::get('/join','App\Http\Controllers\Auth\AuthController@register')->name('register');

    Route::post('/process-join','App\Http\Controllers\Auth\AuthController@process_register')->name('process-register');

    Route::get('/logout','App\Http\Controllers\Auth\AuthController@logout')->name('logout');
    
    Route::group(['middleware' => 'auth'], function(){

        Route::get('/','App\Http\Controllers\HomeController@home')->name('home');

        Route::get('/stats','App\Http\Controllers\HomeController@stats');

        Route::post('/stats','App\Http\Controllers\HomeController@stats');

    });
});