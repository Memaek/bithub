<?php
/**
 * Created by PhpStorm.
 * User: jie
 * Date: 2018/8/24
 * Time: 15:09
 */

namespace App\Service\News;


use App\Models\SystemMessage;

class SystemMessageInfo
{
    public $message;
    public $publishTime;
    public $status;

    public function selectAllData()
    {
        $data = SystemMessage::where('status',1)
            ->orderBy('publish_time','desc')
            ->get(['message','publish_time']);
        return $data;
    }
}