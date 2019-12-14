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

//后台模板
Route::prefix('admin')->group(function () {
   Route::get('index','Admin\IndexController@index'); //后台首页
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
});

