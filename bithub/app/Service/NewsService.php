<?php
/**
 * Created by PhpStorm.
 * User: jie
 * Date: 2018/8/24
 * Time: 14:59
 */

namespace App\Service;


use App\Service\News\SystemMessageInfo;

class NewsService
{
    public function webMessage()
    {
        $systemMeaasge = new SystemMessageInfo();
        $data = $systemMeaasge->selectAllData();
        return $data;
    }
}