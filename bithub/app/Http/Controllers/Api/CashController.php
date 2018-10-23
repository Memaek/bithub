<?php
/**
 * Created by PhpStorm.
 * User: jie
 * Date: 2018/8/8
 * Time: 15:08
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Service\CashService;
use App\Service\Error;
use Illuminate\Http\Request;

class CashController extends Controller
{
    private $cash;
    public function __construct(CashService $cashService)
    {
        $this->cash = $cashService;
    }

    /**
     * 银行卡账号管理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bankCard(Request $request)
    {
        if(!($request->has('type','card_number','user_id','card_id','mark'))){
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->cash->bankCardManage(
            $request->input('type'),
            $request->input('card_number'),
            $request->input('user_id'),
            $request->input('card_id'),
            $request->input('mark')
        );
        if($data instanceof Error){
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess($data);
    }

    /**
     * 现金充值与提现
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cashRecharge(Request $request)
    {
        if(!($request->has('user_id','trade_type','ordercash','bank_card','mark'))){
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->cash->recharge(
            $request->input('user_id'),
            $request->input('trade_type'),
            $request->input('ordercash'),
            $request->input('bank_card'),
            $request->input('mark')
        );
        if($data instanceof Error){
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess();
    }

    /**
     * 现金提现充值记录列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cashTradeRecord(Request $request)
    {
        if(!($request->exists('user_id'))){
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->cash->tradeRecord(
            $request->input('user_id')
        );
        return $this->responseSuccess($data);
    }
}