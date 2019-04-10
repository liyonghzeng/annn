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
    // return view('welcome');
   phpinfo();
});

//微信/

Route::get('weixin/ui',"WeixinController@nake");
Route::post('weixin/ui',"WeixinController@index");

Route::get('weixin/AccessToren',"WeixinController@AccessToren");
Route::get('weixin/test',"WeixinController@test");










