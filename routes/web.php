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

Route::get('/domain','DomainController@index');
Route::get('/fanyuming','DomainController@createfandomain');
Route::get('/www','DomainController@createwww');
Route::get('/pushurl','DomainController@creatpushurl');
Route::get('/addsonsite','DomainController@addsonsite');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/addpres', 'DomainController@addpres');
Route::get('/addurl', 'DomainController@addurl');
Route::get('/pushtoken', 'BaiduController@pushtoken');

Route::get('/mulu', 'DomainController@createmuluurl');
