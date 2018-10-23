<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\Error;
use App\Service\User\MakeQRCode;
use App\Service\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    private $wallet;

    public function __construct(WalletService $walletService)
    {
        $this->wallet = $walletService;
    }

    /**
     * 生成二维码图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function receiptCode(Request $request)
    {
        if (!($request->has('auth_key'))) {
            return $this->responseFailed(config('error.params_error'));
        }
        $qrCode = new MakeQRCode();
        $data = array(
            'auth_key' => $request->input('auth_key')
        );
        $qrCode->getQrCode(json_encode($data));
        return $this->responseSuccess(['code_url' => env('APP_URL') . '/qrcodes/' . json_encode($data) . '.png']);
    }

    /**
     * 用户之间的欧币转账
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay(Request $request)
    {
        if (!($request->has('pay_user_id', 'receipt_user_id', 'price'))) {
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->wallet->coinTrade(
            $request->input('pay_user_id'),
            $request->input('receipt_user_id'),
            $request->input('price')
        );
        if ($data instanceof Error) {
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess();
    }

    /**
     * 用户交易记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userTradeRecord(Request $request)
    {
        if(!($request->has('user_id'))){
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->wallet->tradeRecord($request->input('user_id'));
        if($data instanceof Error){
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess($data);
    }

    //欧币充值
    public function coinRecharge()
    {
        $data = $this->wallet->coinRechargeAddress();
        return $this->responseSuccess($data);
    }

    /**
     * 冻结用户欧币
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function blockUserCoin(Request $request){
        if(!($request->has('user_id','coin','cash','comment'))){
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->wallet->blocked(
            $request->input('user_id'),
            $request->input('coin'),
            $request->input('cash'),
            $request->input('comment')
        );
        if($data instanceof Error){
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess($data);
    }

    /**
     * 解冻用户欧币
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unblockUserCoin(Request $request){
        if(!($request->has('user_id','coin','cash','comment'))){
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->wallet->unblocked(
            $request->input('user_id'),
            $request->input('coin'),
            $request->input('cash'),
            $request->input('comment')
        );
        if($data instanceof Error){
            return $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess($data);
    }
}
