<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    public function __construct()
    {

    }

    /**
     * 同步服务器时间
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestTime()
    {
        $serverTime = time();
        $data = array(
            'datetime' => $serverTime
        );
        return $this->responseSuccess($data);
    }

    /**
     * 用户注册接口
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse|int
     */
    public function userSignUp(Request $request,User $user)
    {
        if ($request->exists('username','password')) {
            $userName = $request->input('username');
            //登录名可以是手机号、邮箱地址 或者其他,根据登录名校验该登录名是否已经被注册
            if (preg_match("/^1[3456789]\d{9}$/",$userName)) {
                if(User::where('username', $userName)->first() != null){
                    return $this->responseFailed(['code' => '10001','msg' => '该手机号码已注册！']);
                }
            }
            if (preg_match("/\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}/",$userName)) {
                if(User::where('username', $userName)->first() != null){
                    return $this->responseFailed(['code' => '10002','msg' => '该邮箱已注册！']);
                }
            }
            if(User::where('username', $userName)->first() != null){
                return $this->responseFailed(['code' => '10003','msg' => '该用户名已经被使用！']);
            }
            $password = $request->input('password');
            $user->username = $userName;
            $user->password = md5($password);
            $user->reg_ip = $request->getClientIp();
            $user->reg_date = date("Y-m-d H:i:s");
            $user->save();
            return $this->responseSuccess();
        }
    }

    /**
     * 登录接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
       if ($request->exists('username', 'password')) {
            $username = $request->input('username');
            $password = $request->input('password');
            $user = User::where('username', $username)->first();
            if ($user == null || $user->password != md5($password)) {
                return $this->responseFailed(['code' => '10004','msg' => '用户名或密码错误！']);
            } else {
                $auth_key = Uuid::uuid1();
                $auth_secret = Uuid::uuid1();
                $user->auth_key = $auth_key->getHex();
                $user->auth_secret = $auth_secret->getHex();
                $user->login_ip = $request->getClientIp();
                $user->login_date = date("Y-m-d H:i:s");
                $user->save();
                $data = array(
                    'userid' => $user->id,
                    'authkey' => $user->auth_key,
                    'authsecret' => $user->auth_secret
                );
                return $this->responseSuccess($data);
            }
        }
    }

    /**
     * 修改密码接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        if ($request->exists('authkey', 'new_password','old_password' ,'request_date' ,'auth_sign')) {
            $param = array(
                'auth_key' => $request->input('authkey'),
                'new_password' => $request->input('new_password'),
                'old_password' => $request->input('old_password'),
                'request_date' => $request->input('request_date'),
            );
            $user = User::where('auth_key', $param['auth_key'])->first();
            if ( $user == null ) {
                return $this->responseFailed(['code' => '10005' , 'msg' => '不存在该用户信息，无法修改！']);
            }
            if ($user->password != md5($param['old_password'])) {
                return $this->responseFailed(['code' => '10006' , 'msg' => '原密码错误！']);
            }
            if ( abs(time() - $param['request_date']) > 120) {
                return $this->responseFailed(['code' => '10007' , 'msg' => '请求超时！']);
            }
            //签名字符串
            ksort($param);
            $str = '';
            foreach($param as $key => $value){
                $str .= $key.$value;
            }
            $auth_secret = $user->auth_secret;
            $auth_sign = md5($str.$auth_secret);
            //验签
            if ($auth_sign != $request->input('auth_sign')) {
                return $this->responseFailed(['code' => '10008' , 'msg' => '无效的签名！']);
            }
            $user->password = md5($request->input('new_password'));
            $user->save();
            return $this->responseSuccess();
        }
    }
}
