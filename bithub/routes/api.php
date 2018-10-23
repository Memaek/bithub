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

Route::group(['prefix' => '/', 'namespace' => 'Api'], function () {
    Route::get('request/requesttime', 'UserController@requestTime');     //获取服务器时间
    Route::group(['prefix' => '/user'], function () {
        Route::post('/usertoken', 'UserController@userToken');           //获取绑定用户的Token
        Route::get('/captcha', 'UserController@captcha');                //图片验证码
        Route::post('/signup', 'UserController@userSignUp');             //用户注册
        Route::post('/login', 'UserController@login');                   //用户登录
        Route::post('/setpassword', 'UserController@resetPassword');     //修改密码
        Route::post('/getcode', 'UserController@getActivationCode');     //获取邮箱或者手机激活码
        Route::post('/bindemail', 'UserController@bindEmail');           //绑定邮箱
        Route::post('/bindtelephone', 'UserController@bindTelephone');   //绑定手机号码
        Route::post('/userinfo', 'UserController@userInfo');             //用户资料信息及资产信息

    });
    Route::group(['prefix' => '/device'], function () {
        Route::get('/setaddress', 'DeviceController@setAddress');        //绑定钱包地址
        Route::get('/getaddress', 'DeviceController@getAddress');        //获取钱包地址
        Route::get('/getversion', 'DeviceController@getVersion');        //获取挖矿应用版本号
        Route::get('/setversion', 'DeviceController@setVersion');        //更新挖矿应用版本号
    });
    Route::group(['prefix' => '/wallet'], function () {
        Route::post('/qrcode', 'WalletController@receiptCode');              //获取收款二维码--未完
        Route::post('/pay', 'WalletController@pay');                         //转账
        Route::post('/traderecord', 'WalletController@userTradeRecord');     //转账流水记录
        Route::post('/blockusercoin', 'WalletController@blockUserCoin');     //冻结用户欧币
        Route::post('/unblockusercoin', 'WalletController@unblockUserCoin'); //解冻用户欧币
    });

    Route::group(['prefix' => '/cash'], function () {
        Route::post('/bankcardmanage', 'CashController@bankCard');         //银行卡账号管理
        Route::post('/cashrecharge', 'CashController@cashRecharge');       //现金充值与转账
        Route::post('/cashtraderecord', 'CashController@cashTradeRecord');  //现金交易记录
    });
    // 交易中心
    Route::group(['prefix' => '/trade'], function () {
        Route::post('/createorder', 'TradeController@createOrder');     //创建订单
        Route::post('/cancelorder', 'TradeController@cancelOrder');     //取消订单
        Route::post('/matchtrade', 'TradeController@matchTrade');       //撮合交易
        Route::post('/orderlist', 'TradeController@orderlist');         //用户交易列表

        Route::get('/testorderid', 'TradeController@testOrderid');      //测试订单号
    });

    // 资讯
    Route::group(['prefix' => '/information'], function () {
        Route::get('/main', 'NewsController@quotationList');             //行情列表
        Route::get('/news', 'NewsController@news');                      //资讯列表
        Route::get('/detail', 'NewsController@detail');                  //详情内容
        Route::get('/announcement', 'NewsController@announcement');      //网站公告
    });
});