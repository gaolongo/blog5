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



//后台登陆
Route::any('admin/login','Admin\IndexController@login');  //登陆添加
Route::any('admin/login_denglu', 'Admin\IndexController@login_denglu'); //后台登陆执行

//后台模板
Route::group(['middleware'=>['Login'],'prefix'=>'/admin/'],function() {

   //后台用户模块 rbac
   Route::get('index','Admin\IndexController@index'); //后台首页
<<<<<<< Updated upstream
   Route::get('cate_add','Admin\IndexController@cate_add'); //分类视图
   Route::post('cate_add_do','Admin\IndexController@cate_add_do'); //分类添加
   Route::any('cate_list','Admin\IndexController@cate_list'); //分类列表
   Route::any('cate_del/{cate_id}','Admin\IndexController@cate_del'); //分类删除
   Route::get('type_add','Admin\IndexController@type_add'); //类型视图
   Route::post('type_add_do','Admin\IndexController@type_add_do'); //类型添加
   Route::any('type_list','Admin\IndexController@type_list'); //类型列表
   Route::get('attr_add','Admin\IndexController@attr_add'); //属性视图
   Route::post('attr_add_do','Admin\IndexController@attr_add_do'); //属性添加
   Route::any('attr_list','Admin\IndexController@attr_list'); //属性列表
   Route::any('attr_del','Admin\IndexController@attr_del'); //属性列表
   Route::any('attr_attrlist','Admin\IndexController@attr_attrlist'); //对应属性列表
   Route::get('goods_add','Admin\IndexController@goods_add'); //商品视图
   Route::get('getAttr','Admin\IndexController@getAttr'); //
   Route::post('goods_add_do','Admin\IndexController@goods_add_do'); //商品添加
   Route::any('goods_list','Admin\IndexController@goods_list'); //商品列表
   Route::any('isshow','Admin\IndexController@isshow'); //商品上下架及点及改
   Route::get('product_add/{goods_id}','Admin\IndexController@product_add'); //货品视图
   Route::post('product_add_do','Admin\IndexController@product_add_do'); //货品添加
=======
   Route::get('admin','Admin\IndexController@admin'); //管理员展示
   Route::group(['middleware' => ['Quanxian']], function () {
        Route::get('admin_insert', 'Admin\IndexController@admin_insert');  //管理员添加
        Route::post('admin_submit', 'Admin\IndexController@admin_submit'); //管理员添加执行
        Route::get('admin_set/{admin_id}', 'Admin\IndexController@admin_set');  //管理员角色设置
        Route::post('admin_role_insert', 'Admin\IndexController@admin_role_insert'); //管理员角色设置执行
        Route::get('admin_delete/{admin_id}', 'Admin\IndexController@admin_delete'); //管理员删除
        //角色
        Route::get('role_select', 'Admin\IndexController@role_select');  //角色添加页面
        Route::get('role_delete/{role_id}', 'Admin\IndexController@role_delete');  //角色删除
        Route::get('right', 'Admin\IndexController@right');   //角色授权
    });
    Route::get('admin_update', 'Admin\IndexController@admin_update');   //管理员密码修改
    Route::post('admin_update_add', 'Admin\IndexController@admin_update_add');  //管理员密码修改执行
    Route::get('role', 'Admin\IndexController@role');    //角色展示
    Route::post('role_insert', 'Admin\IndexController@role_insert');   //角色添加执行
    Route::post('role_right', 'Admin\IndexController@role_right');     //角色权限关联

    
>>>>>>> Stashed changes
});

