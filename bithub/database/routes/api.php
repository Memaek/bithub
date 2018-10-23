<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/','namespace' => 'Admin\Api'],function (){
    Route::get('request/requesttime','UserController@requestTime');     //获取服务器时间
    Route::group(['prefix' => '/user'],function (){
        Route::post('/signup','UserController@userSignUp');             //用户注册
        Route::post('/login','UserController@login');                   //用户登录
        Route::post('/setpassword','UserController@resetPassword');     //修改密码
    });
});