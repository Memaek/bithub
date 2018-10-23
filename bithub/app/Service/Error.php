<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2017/10/12
 * Time: 下午1:55
 */
namespace App\Service;

class Error
{
    private $code;
    private $msg;
    public function __construct($errorkey)
    {
        //TODO 判断配置是否存在，不存在的返回默认错误，如果存在获取code和msg并赋值
        if(array_key_exists($errorkey,config('error')))
        {
            $this->code = config('error.'.$errorkey)['code'];
            $this->msg = config('error.'.$errorkey)['msg'];
        } else {
            $this->code = config('error.system_error')['code'];
            $this->msg = config('error.system_error')['msg'];
        }
    }

    public function toArray()
    {
        return array(
            'code' => $this->code,
            'msg' => $this->msg
        );
    }

    public static function instance($errorkey)
    {
        return new Error($errorkey);
    }
}