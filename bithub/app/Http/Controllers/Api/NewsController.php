<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Service\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public $newsService ;
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * 行情列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function quotationList()
    {
        $data = array(
            array('name' => 'BTC','price' => '€7648.39','float_rate' => '3.30%'),
            array('name' => 'ETH','price' => '€466.04','float_rate' => '0.59%'),
            array('name' => 'ETC','price' => '€16.51','float_rate' => '1.54%'),
            array('name' => 'BCH','price' => '€818.61','float_rate' => '3.62%'),
            array('name' => 'LTC','price' => '€85.19','float_rate' => '2.81%')
        );
        return $this->responseSuccess($data);
    }
    //资讯列表
    public function news(Request $request)
    {
        $number = $request->input('number') ? $request->input('number') : 0;
        $data = News::where('id','>',$number)-> orderBy('id','asc')-> take(5)-> get();
        return $this->responseSuccess($data);
    }

    //详情
    public function detail(Request $request)
    {
        $article_id = $request->input('id');
        $data = News::where('id', $article_id)->get(['title', 'details']);
        return $this->responseSuccess($data);
    }
    /**
     *网站公告
     * @return \Illuminate\Http\JsonResponse
     */
    public function announcement()
    {
        return $this->responseSuccess($this->newsService->webMessage());
    }
}
