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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/domains', 'DomainController@index')->name('domains_list');
Route::get('/domains/add', 'DomainController@add')->name('domain_add');
Route::post('/domains/store', 'DomainController@store')->name('domain_store');
Route::get('/domains/upload', 'DomainController@upload')->name('domain_upload_form');
Route::post('/domains/upload/handle', 'DomainController@handleUpload')->name('domain_upload_handle');

Route::get('/upload', 'UploadController@upload');

Route::get('/parsing/info', 'ParsingController@info')->name('parsing_info');
Route::get('/parsing/start', 'ParsingController@start')->name('parsing_start');

Route::get('/majestic/tasks', 'MajesticController@index')->name('majestic_task_queue');
Route::get('/majestic/chunk', 'MajesticController@getChunk')->name('get_majestic_chunk');
Route::get('/majestic/all', 'MajesticController@getAll')->name('get_majestic_all');

