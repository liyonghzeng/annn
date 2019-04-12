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
    phpinfo();
});

Route::get('weixin/ui','WxController@valid');
//接收微信服务器推送事件
Route::post('weixin/ui','WxController@wxEvent');
Route::get('/weixin/create_menu','WxController@createMenu');     //创建公众号菜单
Route::get('/weixin/get_access_token','WxController@getAccessToken');
Route::get('/weixin/test','WxController@test');
