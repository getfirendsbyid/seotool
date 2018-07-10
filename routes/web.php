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

//系统工具
Route::get('/admin/index','HomeController@welcome');
Route::get('test','BaiduController@test');

//外链工具
Route::get('/admin','LinkController@index');

//外链工具
Route::get('/linktool','LinkController@index');

//百度tool
Route::get('baidu', 'BaiduController@index');
Route::get('baidu/pushtoken', 'BaiduController@pushtoken');
Route::get('baidu/sitemap', 'BaiduController@pushtoken');
Route::get('baidu/yuming', 'BaiduController@pushtoken');
Route::get('baidu/ping', 'BaiduController@ping');
//神马tool

//系统生成
Route::get('sc/ptej', 'LinkController@index');
Route::get('sc/pyej', 'LinkController@index');
Route::get('sc/ptzml', 'LinkController@index');
Route::get('sc/pyzml', 'LinkController@index');

//域名tool
Route::get('yuming','DomainController@index');
Route::get('yuming/list','DomainController@list');
Route::get('yuming/add','DomainController@add');
Route::post('yuming/doadd','DomainController@doadd');
Route::get('yuming/update','DomainController@update');
Route::get('yuming/addteam','DomainController@addteam');
Route::post('yuming/doaddteam','DomainController@doaddteam');

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

