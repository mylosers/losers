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
//退出
Route::any('/exit','User\UserController@exits');
//登陆
Route::any('/login','User\UserController@login');
Route::any('/loginAdd','User\UserController@loginAdd');
Route::any('/loginIndex','User\UserController@index');
//boot测试
Route::any('/bst','Order\MvcController@bst');
//中间件
Route::any('/test','Vip\TestController@test')->middleware('test');

//请先登陆
Route::any('/user','User\UserController@index');
//首页
Route::any('/index','Index\indexController@index');
//购物车
//Route::get('/cart','Cart\IndexController@index')->middleware('check.uid');
Route::any('/goods','Cart\IndexController@index');
Route::any('/goodsList','Cart\IndexController@goodsList'); //商品列表
Route::any('/goodsSelect','Cart\IndexController@goodsSelect'); //商品搜索
Route::any('/set','Cart\IndexController@set'); //商品搜索
Route::any('/get','Cart\IndexController@get'); //商品搜索
Route::any('/cartAdd2/{goods_id}','Cart\IndexController@add');      //添加商品
Route::any('/goodsAdd','Cart\IndexController@goodsAdd');      //添加商品
Route::any('/number','Cart\IndexController@number');      //判断库存
Route::any('/goods/{goods_id}','Cart\IndexController@goods');      //添加商品
Route::any('/goodsDel/{goods_id}','Cart\IndexController@del');      //删除商品
//订单
Route::any('/order','Cart\OrderController@orderList');           //订单列表
Route::any('/orderAdd','Cart\OrderController@add');           //下单
//支付
Route::any('/pay/{oid}','Pay\AlipayController@pay');
//Route::any('/pay','Pay\AlipayController@test');         //测试
Route::any('/pay/alipay/notify','Pay\AlipayController@notify');        //支付宝支付 通知回调 异步
Route::any('/pay/alipay/returns','Pay\AlipayController@aliReturn');        //支付宝支付 通知回调 同步
//个人中心
Route::any('/user','User\UserController@index');        //个人中心
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//在线订座
Route::get('/movie/seat','Movie\IndexController@index');
//微信
Route::get('/weixin/test/token','Weixin\WeixinController@test');
Route::get('/weixin/valid','Weixin\WeixinController@validToken');
Route::get('/weixin/valid1','Weixin\WeixinController@validToken1');
Route::post('/weixin/valid1','Weixin\WeixinController@wxEvent');        //接收微信服务器事件推送
Route::post('/weixin/valid','Weixin\WeixinController@validToken');

Route::get('/weixin/create_menu','Weixin\WeixinController@createMenu');     //创建菜单

Route::get('/form/show','Weixin\WeixinController@formShow');     //表单测试
Route::post('/form/test','Weixin\WeixinController@formTest');     //表单测试
//单发
Route::post('/weixin/send','Weixin\WeixinController@sendAll');
//素材
Route::get('/weixin/fodder','Weixin\WeixinController@fodder');
//测试
Route::get('/weixin/show','Weixin\WeixinController@formShow');
Route::post('/weixin/test','Weixin\WeixinController@formTest');
//单发
Route::get('/weixin/one','Weixin\WeixinController@one');
//微信聊天
Route::get('/weixin/chat','Weixin\WeixinController@chatShow');
Route::get('/weixin/get_msg','Weixin\WeixinController@getChatMsg');
Route::post('/weixin/weixinChat','Weixin\WeixinController@weixinChat');
//微信支付
Route::get('/weixin/pay/test/{order_id}','Weixin\PayController@test');
Route::post('/weixin/pay/notice','Weixin\PayController@notice');
Route::get('/weixin/pay/wxsuccess','Weixin\PayController@WxSuccess');


//微信 JSSDK

Route::get('/weixin/jssdk/test','Weixin\WeixinController@jssdkTest');       // 测试
