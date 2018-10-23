<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\Error;
use App\Service\TradeService;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    private $orderService;

    public function __construct(TradeService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * 创建买单、卖单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder(Request $request)
    {
        if(!($request->has('order_type','user_id','price','target'))){
            return $this->responseFailed(config('error.params_error'));
        }
        $data = null;
        if( $request->input('order_type') == 'buy'){
            $data = $this->orderService->createBuyCoinOrder(
                $request->input('user_id'),
                $request->input('price'),
                $request->input('target')
            );
        }
        if( $request->input('order_type') == 'sell'){
            $data = $this->orderService->createSellCoinOrder(
                $request->input('user_id'),
                $request->input('price'),
                $request->input('target')
            );
        }

        if($data instanceof Error){
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess($data);

        //$data = $this->orderService->match();
    }

    //撮合交易
    public function matchTrade()
    {
        $data = $this->orderService->match();
        if($data instanceof Error){
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess($data);
    }

    /**
     * 撤销交易
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelOrder(Request $request)
    {
        if(!($request->has('order_id'))){
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->orderService->cancel($request->input('order_id'));
        if($data instanceof Error){
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess($data);
    }

    /**
     * 用户买卖单交易列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderlist(Request $request)
    {
        if(!($request->exists('user_id','page','order_type'))){
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->orderService->getlist(
            $request->input('user_id'),
            $request->input('page'),
            $request->input('order_type')
        );
        return $this->responseSuccess($data);
    }

    /**
     * 下发任务
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOrderQueue()
    {
    }

    public function testOrderid()
    {
        $orderid = $this->orderService->testUser(1);
        $data = ['orderid' => $orderid];
        return $this->responseSuccess($data);
    }

    public function hashMac()
    {
        echo strtoupper("e0b94d7435e3");
        exit();
        $maclist = [
            'F82BC800F000',
            'F82BC800F001',
            'F82BC800F002',
            'F82BC800F003',
            'F82BC800F004',
            'F82BC800F005',
            'F82BC800F006',
            'F82BC800F007',
            'F82BC800F008',
            'F82BC800F009',
        ];

        foreach ($maclist as $value) {
            $md5 = base64_encode(md5($value . "b0d499c6cf3ab7a56897f5dee4d548de"));
            $lower = strtolower($md5);
            $lower = str_replace('0', '2', $lower);
            $lower = str_replace('1', '3', $lower);
            $lower = str_replace('i', '4', $lower);
            $lower = str_replace('l', '5', $lower);
            $lower = str_replace('o', '6', $lower);
            $substr = substr($lower, 8, 8);
            $uper = strtoupper($substr);
            echo $value, "=>", $uper, "\n";
        }
    }
}
