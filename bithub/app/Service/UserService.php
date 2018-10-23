<?php
namespace App\Service;

use App\Models\User;
use App\Models\UserWallet;
use App\Service\User\CodeImg;
use App\Service\User\UserInfo;
use App\Service\Utils\Utils;
use App\Service\Wallet\WalletInfo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserService {

    private $email_service;

    public function __construct(EmailService $emailService)
    {
        $this->email_service = $emailService;
    }

    /**
     * 根据user_id获取用户信息
     *
     * @param $userId
     * @return UserInfo|null
     */
    public function getUserInfo($userId)
    {
        return UserInfo::instance($userId);
    }

    /**
     * 同步服务器时间
     *
     * @return array 返回服务器时间
     */
    public function serverTime()
    {
        $serverTime = time();
        $data = array(
            'datetime' => $serverTime
        );
        return $data;
    }

    /**
     * 请求捆绑用户验证码的一个token
     *
     * @return array
     */
    public function bindUserToken()
    {
        $token = Utils::newSerialID();
        Cache::put('token', $token , 6000);
        return array(
            'request_token' => $token,
            'picture_url' => env('APP_URL').'/api/user/captcha?request_token='.$token
        );
    }

    /**
     * 生成验证码图片
     *
     * @param $tmp
     */
    public function codeCaptcha($request_token)
    {
        if(Cache::get('token') != $request_token){
            return Error::instance('token_error');
        }
        return CodeImg::codeImg($request_token);
    }

    /**
     * 用户注册
     *
     * @param $verifyImg
     * @param $userName
     * @param $password
     * @param $reg_ip
     * @return Error|bool
     */
    public function reg($verifyImg,$username,$password,$request_token,$reg_ip)
    {
        $captcha = Cache::get($request_token);
        if ($captcha != $verifyImg) {
            return Error::instance('code_error');
        }
        $user = new UserInfo();
        if($user->instanceByUsername($username) != null) {
           return Error::instance('username_signup');
        }
        DB::beginTransaction();

        $userInfo           = new UserInfo();
        $userInfo->username = $username;
        $userInfo->password = md5($password);
        $userInfo->regIp    = $reg_ip;
        $userInfo->regDate  = date("Y-m-d H:i:s");
        $insertUserId       = $userInfo->create();
        $walletInfo         = new WalletInfo();
        $walletInfo->userId = $insertUserId;
        $result             = $walletInfo->create();

        if($insertUserId && $result){
            DB::commit();
            return true;
        }
        DB::rollback();
        return false;
    }


    /**
     * 用户登录
     *
     * @param $verifyImg
     * @param $username
     * @param $password
     * @param $login_ip
     * @return Error|array
     */
    public function userLogin($verifyImg,$username,$password,$request_token,$login_ip)
    {
        $captcha = Cache::get($request_token);
        if ($captcha != $verifyImg) {
            return Error::instance('code_error');
        }
        $userInfo = User::where('username',$username)->first();
        if($userInfo == null){
            return Error::instance('signup_first');
        }
        if ($userInfo->username != $username || $userInfo->password!= md5($password)) {
            return Error::instance('auth_failed');
        }
        $userInfo->auth_key    = Utils::newSerialID();
        $userInfo->auth_secret = Utils::newSerialID();
        $userInfo->login_ip    = $login_ip;
        $userInfo->login_date  = date("Y-m-d H:i:s");
        $bool = $userInfo->save();

        if(!$bool){
            return Error::instance('db_operate_failed');
        }
        $data = array(
            'user_id' => $userInfo->user_id,
            'auth_key' => $userInfo->auth_key,
            'auth_secret' => $userInfo->auth_secret
        );
        return $data;
    }

    /**
     * 修改密码
     *
     * @param $auth_key
     * @param $new_password
     * @param $old_password
     * @param $request_date
     * @param $auth_sign
     * @return Error
     */
    public function resetPwd($auth_key,$new_password,$old_password,$request_date,$auth_sign,$request_token,$verifyImg)
    {
        $captcha = Cache::get($request_token);
        if ($captcha != $verifyImg) {
            return Error::instance('code_error');
        }
        $user = User::where('auth_key', $auth_key)->first();
        if ($user == null) {
            return Error::instance('user_not_found');
        }
        if ($user->password != md5($old_password)) {
            return Error::instance('oldpassword_error');
        }
        if (abs(time() - $request_date) > 60*60) {
            return Error::instance('request_timeout');
        }
        $sign_param = array(
            'auth_key' => $auth_key,
            'new_password' => $new_password,
            'old_password' => $old_password,
            'request_date' => $request_date
        );
        ksort($sign_param);
        $str = '';
        foreach ($sign_param as $key => $value) {
            $str .= $key . $value;
        }
        $auth_secret = $user->auth_secret;
        $sign = md5($str . $auth_secret);
        //验签
        if ($sign != $auth_sign) {
            return Error::instance('sign_error');
        }
        $user->password = md5($new_password);
        $bool = $user->save();
        if(!$bool){
            return Error::instance('db_operate_failed');
        }
        return $bool;
    }


    /**
     * 获取邮箱激活码
     *
     * @param $userid
     * @param $email
     * @return Error|array
     */
    public function getEmailCode($userid,$email)
    {
        if(!(preg_match("/\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}/", $email))){
            return Error::instance('email_error');
        }
        if(User::where('email',$email)->where('status',1)->first() != null){
            return Error::instance('email_bind');
        }
        $user = User::where('user_id',$userid)->first();
        if($user == null){
            return Error::instance('user_not_found');
        }
        $user->email = $email;
        $user->token = substr(md5($user->username.$user->password.time()),0,6); //创建用于激活识别码;
        $user->token_exptime = time()+60*60;   //过期时间为60分钟后;
        $bool = $user->save();
        if (!$bool) {
            return Error::instance('db_operate_failed');
        }
        //$this->email_service->sendEmail($email,$user->token);
        return array('token' =>$user->token);
    }

    /**
     * 获取手机验证码
     *
     * @param $userid
     * @param $telephone
     * @return Error|array
     */
    public function getTelCode($userid,$telephone)
    {
        if(!(preg_match("/^1[3456789]\d{9}$/",$telephone))){
            return Error::instance('telephone_error');
        }
        if(User::where('telephone',$telephone)->where('tel_status',1)->first() != null){
            return Error::instance('telephone_bind');
        }
        $user = User::where('user_id',$userid)->first();
        if($user == null){
            return Error::instance('user_not_found');
        }
        $user->telephone = $telephone;
        $user->verify_code = substr(md5($user->username.$user->password.time()),0,6);
        $user->verify_exptime = time()+60*60;
        $bool = $user->save();
        if (!$bool) {
           return Error::instance('db_operate_failed');
        }
        //发送手机短信
        return array('token' =>$user->verify_code);
    }

    /**
     * 用户绑定邮箱
     *
     * @param $userid
     * @param $email
     * @param $token
     * @return Error
     */
    public function userEmail($userid,$email,$token)
    {
        $user = User::where('user_id',$userid)->where('email',$email)->first();
        if($user == null){
            return Error::instance('user_not_found');
        }
        if($user->status === 0){
            if(time() > $user->token_exptime){
                return Error::instance('code_timeout');
            }
            if($user->token != $token){
                return Error::instance('email_code_error');
            }
            $user->status = 1;
            $bool = $user->save();
            if(!$bool){
                return Error::instance('db_operate_failed');
            }
            return $bool;
        }
        if($user->status === 1){
            return Error::instance('email_bind');
        }
    }

    /**
     * 用户绑定手机号码
     *
     * @param $userid
     * @param $telephone
     * @param $token
     * @return Error
     */
    public function userTelephone($userid,$telephone,$token)
    {
        $user = User::where('user_id',$userid)->where('telephone',$telephone)->first();
        if($user == null){
            return Error::instance('user_not_found');
        }
        if($user->tel_status === 0){
            if(time() > $user->verify_exptime){
                return Error::instance('code_timeout');
            }
            if($user->verify_code != $token){
                return Error::instance('tel_code_error');
            }
            $user->tel_status = 1;
            $bool = $user->save();
            return $bool;
        }
        if($user->tel_status === 1){
            return Error::instance('telephone_bind');
        }
    }

    /**
     * 用户账号以及资产信息
     *
     * @param $auth_key
     * @return Error|array
     */
    public function userLoginInfo($auth_key)
    {
        $user = User::where('auth_key',$auth_key)->first();
        if($user == null){
            return Error::instance('user_not_found');
        }
        $userAsset = UserWallet::where('user_id',$user->user_id)->first();
        $data = array(
           'username' => $user->username,
           'login_ip' => $user->login_ip,
           'login_date' => $user->login_date,
           'tel_status' => $user->tel_status,
           'email_status' => $user->status,
           'coin' => $userAsset->coin,
           'cash' => $userAsset->cash
        );
        return $data;
    }
}
