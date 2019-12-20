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
//防非法登录
Route::view('/login','Admin\Login\login')->name('login');
//执行登录
Route::post('/login/loginDo','Admin\Login@loginDo');

// Auth::routes();
// Route::get('/home','HomeController@index')->name('home');

// 商品品牌
Route::prefix('brand/')->middleware('auth')->group(function(){
    Route::get('/','Admin\Brand@index');
    Route::get('create','Admin\Brand@create');
    Route::post('store','Admin\Brand@store');
    Route::get('edit/{id}','Admin\Brand@edit');
    Route::post('update/{id}','Admin\Brand@update');
    Route::get('destroy/{id}','Admin\Brand@destroy');
}); 
//商品分类 
Route::prefix('category/')->middleware('auth')->group(function(){
    Route::get('/','Admin\Category@index');
    Route::get('create','Admin\Category@create');
    Route::post('store','Admin\Category@store');
    Route::get('edit/{id}','Admin\Category@edit');
    Route::post('update/{id}','Admin\Category@update');
    Route::get('destroy/{id}','Admin\Category@destroy');
    Route::post('changeAsd','Admin\Category@changeAsd');
    Route::post('changeCateShow','Admin\Category@changeCateShow');
});
//管理员
Route::prefix('admin/')->middleware('auth')->group(function(){
    Route::get('/','Admin\Admin@index');
    Route::get('create','Admin\Admin@create');
    Route::post('store','Admin\Admin@store');
    Route::get('edit/{id}','Admin\Admin@edit');
    Route::post('update/{id}','Admin\Admin@update');
    Route::get('destroy/{id}','Admin\Admin@destroy');
});
//商品
Route::prefix('goods/')->middleware('auth')->group(function(){
    Route::get('/','Admin\Goods@index');
    Route::get('create','Admin\Goods@create');
    Route::post('store','Admin\Goods@store');
    Route::get('edit/{id}','Admin\Goods@edit');
    Route::post('update/{id}','Admin\Goods@update');
    Route::get('destroy/{id}','Admin\Goods@destroy');
    Route::post('change','Admin\Goods@change');
    Route::post('wy','Admin\Goods@wy');
});

//////////////////////////////////////////////////////////////////////
// Route::get('/','Index\Index@index');//首页
// Route::get('prolist','Index\Index@prolist');//全部商品
// Route::post('goodsInfo','Index\Index@goodsInfo');//重新获取全部商品
// Route::get('proinfo/{id}','Index\Index@proinfo');//商品详情页
// Route::get('user','Index\Index@user');//用户
// Route::get('car','Index\Index@car');//购物车列表
// Route::post('changeNum','Index\Index@changeNum');//修改购买数量
// Route::post('getTotal','Index\Index@getTotal');//获取小计
// Route::post('getCount','Index\Index@getCount');//获取总价
// Route::post('del','Index\Index@del');//单删或批删
// Route::get('pay','Index\Index@pay');//结算
// Route::get('submitOrder','Index\Index@submitOrder');//提交订单
// Route::get('success','Index\Index@success');//提交订单成功
// Route::post('addCart','Index\Index@addCart');//加入购物车
// Route::get('address','Index\Index@address');//收货地址
// Route::post('getArea','Index\Index@getArea');//地区信息
// Route::post('save','Index\Index@save');//添加收货地址
// Route::get('addAddress','Index\Index@addAddress');//收货地址列表

// Route::get('login','Index\Login@login');//登录视图
// Route::post('loginDo','Index\Login@loginDo');//登录

// Route::get('reg','Index\Reg@reg');//注册视图
// Route::post('regDo','Index\Reg@regDo');//注册
// Route::post('send','Index\Reg@send');//验证是否发送成功
// Route::get('/send_email','MailController@send_email');

// Route::get('payDo/{id}','Index\Order@payDo');//支付
// Route::get('returnUrl','Index\Order@returnUrl');//同步
// Route::get('notifyUrl','Index\Order@notifyUrl');//异步