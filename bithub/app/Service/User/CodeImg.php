<?php
namespace App\Service\User;

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Cache;

class CodeImg {
    /**
     * 生成验证码图片
     * @param $tmp
     */
    public static function codeImg($token)
    {
        //生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder();
        //可以设置图片宽高及字体
        $builder->build($width = 116, $height = 40, $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();
        Cache::put($token, $phrase , 6000);
        ob_clean();
        return response($builder->output())->header('Content-type','image/jpeg');
    }
}