<?php

namespace App\Http\Controllers\Api;


use App\Service\Error;
use App\Service\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * 同步服务器时间
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestTime()
    {
        return $this->responseSuccess($this->userService->serverTime());
    }

    /**
     * 请求捆绑用户验证码的一个token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userToken()
    {
        $data =  $this->userService->bindUserToken();
        return $this->responseSuccess($data);
    }
    /**
     * 生成验证码图片
     *
     * @return mixed 返回图片验证码
     */
    public function captcha(Request $request)
    {
        if (!($request->has('request_token'))) {
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->userService->codeCaptcha($request->input('request_token'));
        if ($data instanceof Error) {
            return $this->responseFailed($data->toArray());
        }
        return $data;
    }

    /**
     * 用户注册接口
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userSignUp(Request $request)
    {
        if (!($request->has('username', 'password', 'verifyimg','request_token'))) {
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->userService->reg(
            $request->input('verifyimg'),
            $request->input('username'),
            $request->input('password'),
            $request->input('request_token'),
            $request->getClientIp()
        );
        if ($data instanceof Error) {
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess();
    }

    /**
     * 登录接口
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if (!($request->has('username', 'password', 'verifyimg','request_token'))) {
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->userService->userLogin(
            $request->input('verifyimg'),
            $request->input('username'),
            $request->input('password'),
            $request->input('request_token'),
            $request->getClientIp()
        );
        if ($data instanceof Error) {
            return $this->responseFailed($data->toArray());
        } else {
            return $this->responseSuccess($data);
        }
    }

    /**
     * 修改密码接口
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        if (!($request->has('auth_key', 'new_password', 'old_password', 'request_date', 'auth_sign','request_token','verifyimg'))) {
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->userService->resetPwd(
            $request->input('auth_key'),
            $request->input('new_password'),
            $request->input('old_password'),
            $request->input('request_date'),
            $request->input('auth_sign'),
            $request->input('request_token'),
            $request->input('verifyimg')
        );
        if ($data instanceof Error) {
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess();
    }

    /**
     * 获取绑定邮箱/绑定手机号码的激活码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivationCode(Request $request)
    {
        if ($request->has('email')) {
            $data = $this->userService->getEmailCode(
                $request->input('user_id'),
                $request->input('email')
            );
            if ($data instanceof Error) {
                return $this->responseFailed($data->toArray());
            }
            return $this->responseSuccess($data);

        }
        if ($request->has('telephone')) {
            $data = $this->userService->getTelCode(
                $request->input('user_id'),
                $request->input('telephone')
            );
            if ($data instanceof Error) {
                return $this->responseFailed($data->toArray());
            }
            return $this->responseSuccess($data);
        }
        return $this->responseFailed(config('error.params_error'));
    }

    /**
     * 绑定邮箱
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bindEmail(Request $request)
    {
        if (!($request->has('user_id', 'email', 'token'))) {
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->userService->userEmail(
            $request->input('user_id'),
            $request->input('email'),
            $request->input('token')
        );
        if ($data instanceof Error) {
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess();
    }

    /**
     * 绑定手机号
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bindTelephone(Request $request)
    {
        if (!($request->has('user_id', 'telephone', 'token'))) {
            return $this->responseFailed(config('error.params_error'));

        }
        $data = $this->userService->userTelephone(
            $request->input('user_id'),
            $request->input('telephone'),
            $request->input('token')
        );
        if ($data instanceof Error) {
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess();
    }

    /**
     * 用户账号及资产信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userInfo(Request $request)
    {
        if(!$request->has('auth_key')){
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->userService->userLoginInfo(
            $request->input('auth_key')
        );
        if ($data instanceof Error) {
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess($data);
    }
}
