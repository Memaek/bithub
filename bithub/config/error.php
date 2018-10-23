<?php
/**
 * Created by PhpStorm.
 * User: jie
 * Date: 2018/7/20
 * Time: 15:36
 */
return [
    'params_error'                    => ['code' => 1001, 'msg' => '提交参数错误'],
    'code_error'                      => ['code' => 1002, 'msg' => '验证码错误'],
    'request_timeout'                 => ['code' => 1003, 'msg' => '请求超时'],
    'sign_error'                      => ['code' => 1004, 'msg' => '无效签名'],
    'code_timeout'                    => ['code' => 1005, 'msg' => '激活码已失效'],
    'signup_first'                    => ['code' => 1006, 'msg' => '请先注册'],
    'system_error'                    => ['code' => 1007, 'msg' => '系统错误'],
    //
    'username_signup'                 => ['code' => 2001, 'msg' => '用户名已被占用'],
    'user_not_found'                  => ['code' => 2002, 'msg' => '用户信息不存在'],
    'oldpassword_error'               => ['code' => 2003, 'msg' => '原密码错误'],
    'auth_failed'                     => ['code' => 2004, 'msg' => '用户名或密码错误'],
    //
    'email_error'                     => ['code' => 3001, 'msg' => '邮箱格式不正确'],
    'email_bind'                      => ['code' => 3002, 'msg' => '该邮箱已绑定'],
    'email_code_error'                => ['code' => 3003, 'msg' => '邮箱激活码错误'],
    //
    'telephone_error'                 => ['code' => 4001, 'msg' => '非法的手机号码'],
    'telephone_bind'                  => ['code' => 4002, 'msg' => '该手机号已绑定'],
    'tel_code_error'                  => ['code' => 4003, 'msg' => '手机验证码错误'],
    //
    'mac_addr_error'                  => ['code' => 5001, 'msg' => '绑定钱包地址失败'],
    'mac_not_found'                   => ['code' => 5002, 'msg' => '此设备不存在'],
    'coin_not_enough'                 => ['code' => 5003, 'msg' => '数量不足'],
    //
    'bankcard_added'                  => ['code' => 6001, 'msg' => '该卡已存在，请勿重复添加'],
    'bankcard_not_exists'             => ['code' => 6002, 'msg' => '该卡不存在'],
    //
    'wallet_not_found'                => ['code' => 7001, 'msg' => '钱包信息不存在'],
    'wallet_cash_not_enough'          => ['code' => 7002, 'msg' => '现金不足'],
    'wallet_coin_not_enough'          => ['code' => 7003, 'msg' => '金币不足'],
    'wallet_blocked_cash_not_enough'  => ['code' => 7004, 'msg' => '冻结现金不足'],
    'wallet_blocked_coin_not_enough'  => ['code' => 7005, 'msg' => '冻结金币不足'],
    'wallet_cash_blocked_error'       => ['code' => 7006, 'msg' => '现金冻结失败'],
    'wallet_coin_blocked_error'       => ['code' => 7007, 'msg' => '金币冻结失败'],
    'wallet_update_error'             => ['code' => 7008, 'msg' => '钱包更新失败'],
    //
    'trade_order_create_error'        => ['code' => 8001, 'msg' => '交易订单创建失败'],
    'trade_order_not_found'           => ['code' => 8002, 'msg' => '交易订单未找到'],
    'trade_finish_order_create_error' => ['code' => 8003, 'msg' => '交易结束订单创建失败'],
    'trade_finish_order_update_error' => ['code' => 8004, 'msg' => '交易状态更新失败'],
    'trade_order_delete_error'        => ['code' => 8005, 'msg' => '删除交易订单失败'],
    'trade_order_update_error'        => ['code' => 8006, 'msg' => '订单更新失败'],

    'match_order_not_exists'          => ['code' => 8007, 'msg' => '待撮合订单不存在'],
    'match_order_price_error'         => ['code' => 8008, 'msg' => '待撮合订单价格不匹配'],
    'match_order_error'               => ['code' => 8009, 'msg' => '撮合交易订单出错'],
    //
    'db_operate_failed'               => ['code' => 9001, 'msg' => '数据库操作失败'],
    'record_is_null'                  => ['code' => 9002, 'msg' => '记录为空'],
    'token_error'                     => ['code' => 1008, 'msg' => '验证码获取失败'],


];