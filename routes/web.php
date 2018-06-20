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

Auth::routes();

Route::get('/', function () {
   return redirect('login');
});

//外链工具
Route::get('/linktool','LinkController@index');

//百度tool
Route::get('baidu/pushtoken', 'BaiduController@pushtoken');

//神马tool

//域名tool
Route::get('/yuming','DomainController@index');
Route::get('/domain','DomainController@index');
Route::get('/fanyuming','DomainController@createfandomain');
Route::get('/www','DomainController@createwww');
Route::get('/pushurl','DomainController@creatpushurl');
Route::get('/addsonsite','DomainController@addsonsite');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/addpres', 'DomainController@addpres');
Route::get('/addurl', 'DomainController@addurl');

Route::get('/mulu', 'DomainController@createmuluurl');
Route::get('/mulu1', 'DomainController@muluurl');
Route::get('/fy', 'FyController@index');
Route::get('/bijiao', 'DomainController@bijiao');

Route::get('baoxian', 'DomainController@baoxianurl');

