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

Route::get('/', function () {
    return view('welcome');
});


Route::post('/api/dequeue/append', 'Teleforge\TeleForge@append');
Route::delete('/api/dequeue/eject', 'Teleforge\TeleForge@eject');
Route::delete('/api/dequeue/pop', 'Teleforge\TeleForge@pop');
Route::post('/api/dequeue/prepend', 'Teleforge\TeleForge@prepend');
Route::get('/api/dequeue/show', 'Teleforge\TeleForge@show');
Route::delete('/api/dequeue/close', 'Teleforge\TeleForge@close');

