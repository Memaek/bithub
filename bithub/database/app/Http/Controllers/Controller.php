<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 成功响应返回状态
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($data = null)
    {
        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * 失败响应返回状态
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseFailed($data = null)
    {
        return response()->json(['success' => false, 'data' => $data]);
    }
}
