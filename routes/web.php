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

Route::view('/', 'index');

Auth::routes();

Route::resource('boards', 'BoardsController')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::post('/tasks/{board}', 'TasksController@store');
    Route::delete('/tasks/{task}', 'TasksController@destroy');
    Route::post('/cards/{task}', 'CardsController@store');
    Route::delete('/cards/{card}', 'CardsController@destroy');
    Route::patch('/cards/{card}', 'CardsController@update');
});
