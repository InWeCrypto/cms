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
    return '``';
});


Route::group(['prefix' => 'v2'], function($router){
    $router->any('doc', function () {
        return file_get_contents('doc.html');
    });
    // 用户登录
    $router->post('login','AdminController@login');
    // 获取短信验证
    $router->post('get_code','AdminController@getLoginCode');
    $router->group([], function($router){
    // $router->group(['middleware'=>'auth.jwt'], function($router){

        // 项目
        $router->group(['prefix'=>'category'], function($router){
            // 获取项目类型列表
            $router->get('types', 'CategoryController@getTypes');
            // 项目描述
            $router->resource('{cat_id}/desc', 'CategoryDescController');
            // 项目浏览器
            $router->resource('{cat_id}/explorer', 'CategoryExplorerController');
            // 项目媒体
            $router->resource('{cat_id}/media', 'CategoryMediaController');
            // 项目结构
            $router->resource('{cat_id}/structure', 'CategoryStructureController');
            // 项目钱包
            $router->resource('{cat_id}/wallet', 'CategoryWalletController');
            // 项目行业标签
            $router->resource('{cat_id}/industry', 'CategoryIndustryController');
            // 项目空投
            $router->resource('candy_bow', 'CandyBowController');
            // 项目介绍
            $router->resource('{cat_id}/presentation', 'CategoryPresentationController');
            // 项目用户评论管理
            $router->resource('user','CategoryUserController');
            // 用户评论回复
            $router->resource('user/{id}/reply','CategoryUserCommentController');
        });
        $router->resource('category', 'CategoryController');
        // 文章
        $router->group(['prefix'=>'article'], function($router){
            // 获取文章类型列表
            $router->get('types', 'ArticleController@getTypes');
            // 文章标签管理
            $router->resource('tags', 'ArticleTagInfoController');
            // 文章标签
            $router->resource('{art_id}/tags', 'ArticleTagsController');
            // 文章抄送
            $router->resource('{art_id}/cc', 'ArticleCcController');
            // 文章评论
            $router->resource('comment', 'ArticleCommentController');
        });
        $router->resource('article', 'ArticleController');
        // 广告
        $router->resource('ads', 'AdsController');
        // 交易所公告
        $router->resource('ex_notice', 'ExchangeNoticeController');
        // 交易所爬虫
        $router->group(['prefix' => 'ex_notice_spider'], function ($router){
            $router->get('{id}', 'ExchangeNoticeSpiderController@show')->where('id','[0-9]+');
            $router->delete('{id}', 'ExchangeNoticeSpiderController@destroy')->where('id','[0-9]+');
            $router->get('keys', 'ExchangeNoticeSpiderController@keys');
            $router->post('{id}/online', 'ExchangeNoticeSpiderController@stroe');
            $router->post('', 'ExchangeNoticeSpiderController@index');
        });
        // 搜索关键字
        $router->resource('serach_keyword', 'SerachKeywordController');
        // 菜单
        $router->resource('menu', 'MenuController');
        // 菜单分组
        $router->group(['prefix'=>'menu_group'], function($router){
            $router->resource('{gid}/menus', 'MenuGroupRelationController');
        });
        $router->resource('menu_group', 'MenuGroupController');
        // 用户关联菜单组
        $router->group(['prefix'=>'admin'], function($router){
            $router->put('{user_id}/menu_groups', 'AdminController@updateMenuGroup');
        });
        // 管理员列表
        $router->resource('admin', 'AdminController');
        // 用户列表
        $router->group(['prefix'=>'user'], function($router){
            $router->post('{id}/send_sys_msg', 'UserController@sendSysMsg');
            $router->put('{id}/frozen', 'UserController@frozen');
        });
        $router->resource('user', 'UserController');
        // 上传文件
        $router->group(['prefix'=>'upload'], function($router){
            $router->any('img', 'UploadController@img');
            $router->any('video', 'UploadController@video');
            $router->any('file', 'UploadController@file');
        });

        // 代币类型
        $router->resource('wallet_category', 'WalletCategoryController');
        // 代币列表
        $router->resource('gnt_category', 'GntCategoryController');
        // 意见反馈
        $router->resource('feedbackc', 'FeedbackController');

    });
});
