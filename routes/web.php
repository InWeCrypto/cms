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
    return '`';
});

Route::get('/doc', function () {
    return file_get_contents('doc.html');;
});

Route::group(['prefix' => 'v1'], function($router){
    // 登录
    $router->post('login','AdminController@login');

    Route::group(['middleware'=>'jwt.auth'], function($router){
        // 管理员管理
        $router->group(['prefix'=>'admin'], function($router){
            $router::resource('main', 'AdminController');
        });
        // 用户管理
        $router->group(['prefix'=>'user'], function($router){
            $router::resource('main', 'UserController');
        });
        // 项目
        $router->group(['prefix'=>'project'], function($router){
            // 项目列表
            $router->resource('main', 'ProjectController');
            // 项目介绍
            $router->resource('detail', 'ProjectDetailController');
            // 项目行情
            $router->resource('time_price', 'ProjectTimePriceController');
            // 项目市场
            $router->resource('market', 'ProjectMarketController');
            // 项目浏览器
            $router->resource('explorer', 'ProjectExplorerController');
            // 项目钱包
            $router->resource('wallet', 'ProjectWalletController');
            // 项目媒体
            $router->resource('media', 'ProjectMediaController');
            // ICO细则 (未上线项目)
            // 项目标签
            $router->resource('tag', 'ProjectTagController');
            // 项目评论
            
            // 项目类型列表        
            $router->get('type/{id?}', 'ProjectController@getType');
            // 项目方块类型
            $router->get('grid_type/{id?}', 'ProjectController@getGridType');
        });
        // 测评
        $router->group(['prefix'=>'ico_assess'], function($router){
            // ICO 测评列表
            $router->resource('main', 'IcoAssessController');
            // ICO 测评 - 结构
            $router->resource('structure', 'IcoAssessStructureController');
            // ICO 测评 - 发行情况
            $router->resource('issue_info', 'IcoAssessIssueInfoController');
            // ICO 测评 - 项目数据分析
            $router->resource('project_analyse', 'IcoAssessProjectAnalyseController');
            // ICO 测评 - 标签
            $router->resource('tag', 'IcoAssessTagController');
            // ICO 测评 - 评论
            $router->resource('comment', 'IcoAssessCommentController');
        });
        // 文章列表
        $router->group(['prefix'=>'article'], function($router){
            // 资讯列表
            $router->resource('main', 'ArticleController');
            // 资讯标签
            $router->resource('tag', 'ArticleTagController');
            // 资讯抄送栏目
            $router->resource('cc_category', 'ArticleCcController');
        });
        // 项目空投
        $router->group(['prefix'=>'candy_bow'], function($router){
            $router->resource('main', 'CandyBowController');
        });
        // tag标签集合
        $router->group(['prefix'=>'tag'], function($router){
            $router->resource('main', 'TagController');
        });
        // ico描述管理
        $router->group(['prefix'=>'ico'], function($router){
            $router->resource('main', 'IcoController');
        });
        // 上传文件
        $router->group(['prefix'=>'upload'], function($router){
            $router->any('img', 'UploadController@img');
            $router->any('video', 'UploadController@video');
            $router->any('file', 'UploadController@file');
        });
    });
});