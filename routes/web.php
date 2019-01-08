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


Route::get('/user',function(){
    echo 'User' ;
});


//view视图
Route::view('/wac','index/wac',['code'=>1213]);

Route::any('/info','User@info');

Route::any('/order/{id}','Order\IndexController@detail');
#带条件查询
Route::any('/user/{id}','Order\IndexController@user');
#添加
Route::any('/insert','Order\IndexController@insert');
#修改
Route::any('/update/{id}','Order\IndexController@update');
#删除
Route::any('/delete/{id}','Order\IndexController@delete');
#查询
Route::any('/select','Order\IndexController@select');
//路由参数
Route::any('/one','Order\IndexController@one');
//布局测试
Route::any('/layout','Order\LayouController@layout');
//注册
Route::any('/request','User\UserController@request');
Route::any('/requestAdd','User\UserController@requestAdd');
//登陆
Route::any('/login','User\UserController@login');
Route::any('/loginAdd','User\UserController@loginAdd');
Route::any('/loginIndex','User\UserController@index');
//boot测试
Route::any('/bst','Order\MvcController@bst');
//中间件
Route::any('/test','Vip\TestController@test')->middleware('test');

//
Route::get('/user','User\UserController@index')->middleware('check.login.token');





