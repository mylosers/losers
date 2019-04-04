<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('/goods',GoodsController::class);
    //微信
    $router->resource('/wechat/user',WechatUserController::class);
    //用户管理
    $router->any('/wechat/WxUser','WechatUserController@WxUser');
    $router->any('/wechat/WxBlackUserList','WechatUserController@WxBlackUserList');
    $router->any('/wechat/WxBlackUserAdd','WechatUserController@WxBlackUserAdd');
    $router->any('/wechat/WxBlackUserOff','WechatUserController@WxBlackUserOff');
    $router->any('/wechat/menuAdd','WechatUserController@menuAdd');    //自定菜单
    $router->any('/wechat/menuInfo','WechatUserController@menuInfo');    //自定菜单
    $router->any('/wechat/fileInfo','WechatUserController@fileInfo');    //临时文件上传页面
    $router->any('/wechat/filePath','WechatUserController@filePath');    //新增临时素材
    $router->any('/wechat/filePathList','WechatUserController@filePathList');    //获取临时素材
    $router->any('/wechat/upload','WechatUserController@upload');    //文件上传
    $router->any('/wechat/materialList','WechatUserController@materialList');    //文件上传
    $router->any('/wechat/MassAll','WechatUserController@MassAll');    //群发页面
    $router->any('/wechat/MassAllAdd','WechatUserController@MassAllAdd');    //群发页面
    $router->any('/wechat/WxCode','WechatUserController@WxCode');    //二维码
    $router->any('/wechat/test','WechatUserController@test');    //客服页面
    $router->any('/wechat/testAdd','WechatUserController@testAdd');    //客服
    $router->any('/wechat/testList','WechatUserController@testList');    //获取用户消息
    //标签
    $router->resource('/wechat/tag',WechatTagController::class);
    $router->any('/wechat/wxTag','WechatTagController@getWxtag');
    $router->any('/wechat/addWxTag','WechatTagController@getAddtag');
    $router->any('/wechat/addWxTags','WechatTagController@getAddtags');
    $router->any('/wechat/deleteWxTags','WechatTagController@getDeletetags');
    $router->any('/wechat/updateWxTags','WechatTagController@getUpdatetags');
    $router->any('/wechat/addUpdateWxTags','WechatTagController@getUpdateAddtags');
    $router->any('/wechat/tagAll','WechatTagController@tagAll');//标签群发视图
    $router->any('/wechat/tagsAllAdd','WechatTagController@tagsAllAdd');//标签群发
});
