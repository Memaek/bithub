<?php
namespace App\Service;


use App\Models\Bank;
use App\Models\CashOrder;
use App\Service\Wallet\BankInfo;
use App\Service\Wallet\CashOrderInfo;

class CashService
{
    /**
     * 银行卡账号管理
     * @param $type
     * @param null $card_number
     * @param null $user_id
     * @param null $card_id
     * @return Error|bool
     */
    public function bankCardManage($type,$card_number=null,$user_id=null,$card_id=null,$mark=null)
    {
        $bank = new BankInfo();
        if($type == 'add'){
            if($bank->selectByUidAnNumber($user_id,$card_number) != null){
                return Error::instance('bankcard_added');
            }
            $bankInfo         = new BankInfo();
            $bankInfo->userId = $user_id;
            $bankInfo->mark   = $mark;
            $bankInfo->number = $card_number;
            $addRes           = $bankInfo->create();

            if(!$addRes){
                return Error::instance('db_operate_failed');
            }
            return true;
        }
        if($type == 'delete'){
            $bankInfo = $bank->selectById($card_id);
            if($bankInfo == null){
                return Error::instance('bankcard_not_exists');
            }
            if(!$bankInfo->delete()){
                return Error::instance('db_operate_failed');
            }
            return true;
        }
        if($type == 'edit'){
            $bankInfo = $bank->selectById($card_id);
            if($bankInfo == null){
                return Error::instance('bankcard_not_exists');
            }
            $bankInfo->number = $card_number;
            $bankInfo->mark = $mark;
            if(!$bankInfo->update()){
                return Error::instance('db_operate_failed');
            }
            return true;
        }
        if($type == 'show'){
            return $bank->selectAllData($user_id);
        }
        return Error::instance('system_error');
    }

    /**
     * 现金充值与提现
     * @param $user_id
     * @param $trade_type
     * @param $account
     * @param $ordercash
     * @param $bank_card
     * @param null $mark
     */
    public function recharge($user_id,$trade_type,$ordercash,$bank_card,$mark=Null)
    {
        $cashOrderInfo = new CashOrderInfo();
        $cashOrderInfo->orderId         = 'DD'.date('YmdHis').mt_rand(1000, 9999);
        $cashOrderInfo->userId          = $user_id;
        $cashOrderInfo->tradeType       = $trade_type;
        $cashOrderInfo->orderCash       = $ordercash;
        $cashOrderInfo->bankCard        = $bank_card;
        $cashOrderInfo->createDatetime  = date("Y-m-d H:i:s");
        $bool = $cashOrderInfo->create();
        if(!$bool){
            return Error::instance('db_operate_failed');
        }
        return true;
    }

    /**
     * 现金交易记录
     * @param $userId
     * @param int $page
     * @return mixed
     */
    public function tradeRecord($userId,$page=1)
    {
        $cashOrderInfo = new CashOrderInfo();
        return $cashOrderInfo->selectFileds($userId);
    }
}
