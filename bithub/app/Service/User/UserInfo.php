<?php
/**
 * Created by PhpStorm.
 * User: jie
 * Date: 2018/8/6
 * Time: 15:47
 */

namespace App\Service\User;

use App\Models\User;

class UserInfo
{
    public $userId;
    public $username;
    public $password;
    public $email;
    public $status;
    public $telephone;
    public $telStatus;
    public $verifyCode;
    public $verifyExptime;
    public $authKey;
    public $authSecret;
    public $loginIp;
    public $loginDate;
    public $regIp;
    public $regDate;
    public $token;
    public $tokenExptime;

    public function toArray()
    {
        return [
            //'user_id'     =>  $this->userId,
            'username'      =>  $this->username,
            'password'      =>  $this->password,
            'email'         =>  $this->email,
            'status'        =>  $this->status,
            'telephone'     =>  $this->telephone,
            'tel_status'    =>  $this->telStatus,
            'verify_code'   =>  $this->verifyCode,
            'verify_exptime'=>  $this->verifyExptime,
            'auth_key'      =>  $this->authKey,
            'auth_secret'   =>  $this->authSecret,
            'login_ip'      =>  $this->loginIp,
            'login_date'    =>  $this->loginDate,
            'reg_ip'        =>  $this->regIp,
            'reg_date'      =>  $this->regDate,
            'token'         =>  $this->token,
            'token_exptime' =>  $this->tokenExptime
        ];
    }

    public function create()
    {
        $userModel = User::create($this->toArray());
        return $userModel->user_id;
    }

    /**
     * @param $userId
     * @return UserInfo|null
     */
    public  function instanceByUsername($username)
    {
        $user = User::where('username', $username)->first();
        if ($user == null) {
            return null;
        }
        $userInfo = new User();
        $userInfo->userId     = $user->user_id;
        $userInfo->username   = $user->username;
        $userInfo->password   = $user->password;
        $userInfo->email      = $user->email;
        $userInfo->telephone  = $user->telephone;
        $userInfo->telStatus  = $user->tel_status;
        $userInfo->loginIp    = $user->login_ip;
        $userInfo->loginDate  = $user->login_date;
        $userInfo->status     = $user->status;
        $userInfo->verifyCode = $user->verify_code;
        $userInfo->verifyExptime = $user->verify_exptime;
        $userInfo->token         = $user->token;
        $userInfo->authKey       = $user->auth_key;
        $userInfo->authSecret    = $user->auth_secret;
        $userInfo->token_exptime = $user->token_exptime;
        return $userInfo;
    }

    /**
     * @param $userId
     * @return UserInfo|null
     */
    public static function instance($userId)
    {
        $user = User::where('user_id', $userId)->first();
        if ($user == null) {
            return null;
        }
        $userInfo = new User();
        $userInfo->userId     = $user->user_id;
        $userInfo->username   = $user->username;
        $userInfo->password   = $user->password;
        $userInfo->email      = $user->email;
        $userInfo->telephone  = $user->telephone;
        $userInfo->telStatus  = $user->tel_status;
        $userInfo->loginIp    = $user->login_ip;
        $userInfo->loginDate  = $user->login_date;
        $userInfo->status     = $user->status;
        $userInfo->verifyCode = $user->verify_code;
        $userInfo->verifyExptime = $user->verify_exptime;
        $userInfo->token         = $user->token;
        $userInfo->authKey       = $user->auth_key;
        $userInfo->authSecret    = $user->auth_secret;
        $userInfo->token_exptime = $user->token_exptime;
        return $userInfo;
    }
}