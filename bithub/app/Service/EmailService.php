<?php
namespace App\Service;

use Illuminate\Support\Facades\Mail;


class EmailService {
    /**
     * 发送邮件
     * @param $userName
     */
    public function sendEmail($userName,$token)
    {
        Mail::raw('邮箱激活验证码：'.$token,function($message) use ($userName){
            $message->subject('用户帐号激活！');
            $message->to($userName);
        });
    }
}
