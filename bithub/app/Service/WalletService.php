<?php

namespace App\Service;


use App\Models\User;
use App\Service\User\UserInfo;
use App\Service\Utils\Utils;
use App\Service\Wallet\BlockedRecordInfo;
use App\Service\Wallet\CoinTradeRecordInfo;
use App\Service\Wallet\WalletInfo;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function getWalletInfo($userid)
    {
        return WalletInfo::instance($userid);
    }
    /**
     * 转账交易
     *
     * @param $payuserid
     * @param $reciveuserid
     * @param $coin
     *
     * @return Error|bool
     */
    public function coinTrade($payuserid, $reciveuserid, $coin)
    {
        $payUser     =  UserInfo::instance($payuserid);
        $receiptUser =  UserInfo::instance($reciveuserid);
        if ($payUser == null || $receiptUser == null) {
            return Error::instance('user_not_found');
        }
        $pay = WalletInfo::instance($payuserid);
        if ($pay == null) {
            return Error::instance('wallet_not_found');
        }
        if ($pay->coin < $coin) {
            return Error::instance('wallet_coin_not_enough');
        }

        DB::beginTransaction();
        //付款方
        $pay->coin -= $coin;
        $pay_bool  =  $pay->update();
        //收款方
        $rec = WalletInfo::instance($reciveuserid);
        $rec ->coin  += $coin;
        $receipt_bool = $rec->update();
        //交易记录
        $coinTrade                = new CoinTradeRecordInfo();
        $coinTrade->payUserid     = $payUser->userId;
        $coinTrade->receiptUserid = $receiptUser->userId;
        $coinTrade->price         = $coin;
        $insert                   = $coinTrade->create();
        if (!($pay_bool && $receipt_bool && $insert)) {
            DB::rollback();
            return true;
        }
        DB::commit();
        return true;
    }

    /**
     * 欧币交易流水记录
     *
     * @param $userId
     *
     * @return Error
     */
    public function tradeRecord($userId)
    {
        $coinTradeRecordInfo = new CoinTradeRecordInfo();
        $record = $coinTradeRecordInfo->selectRecordList($userId);
        if (!$record) {
            return Error::instance('record_is_null');
        }
        foreach ($record as $item) {
            if ($item->pay_user_id == $userId) {
                $item->price = 0 - $item->price;
            }
            $payUserName            = UserInfo::instance($item->pay_user_id);
            $item->pay_username     = $payUserName->username;
            $receiptUserName        = UserInfo::instance($item->receipt_user_id);
            $item->receipt_username = $receiptUserName->username;
        }
        return $record;
    }


    /**
     * 冻结用户金币
     *
     * @param $userid
     * @param $num
     * @param $coment
     *
     * @return Error|bool
     */
    public function blocked($userid, $coin, $cash, $comments)
    {
        $walletInfo = WalletInfo::instance($userid);
        if ($walletInfo == null) {
            return Error::instance('wallet_not_found');
        }
        if ($walletInfo->coin < $coin) {
            return Error::instance('wallet_coin_not_enough');
        }
        if ($walletInfo->cash < $cash) {
            return Error::instance('wallet_cash_not_enough');
        }

        DB::beginTransaction();

        $walletInfo->coin        -= $coin;
        $walletInfo->blockedCoin += $coin;
        $walletInfo->cash        -= $cash;
        $walletInfo->blockedCash += $cash;
        $isSuccess                = $walletInfo->update();
        if (!$isSuccess) {
            DB::rollBack();
            return Error::instance('wallet_update_error');
        }
        $record = new BlockedRecordInfo();
        $record->orderid  = Utils::newSerialID();
        $record->userid   = $walletInfo->userId;
        $record->coin     = 0 - $coin;
        $record->cash     = 0 - $cash;
        $record->comments = $comments;
        $isSuccess        = $record->create();

        if (!$isSuccess) {
            DB::rollBack();
            return Error::instance('wallet_update_error');
        }
        DB::commit();
        return true;
    }


    /**
     * 解冻用户金币
     *
     * @param $userid
     * @param $num
     * @param $comments
     *
     * @return Error|bool
     */
    public function unblocked($userid, $coin, $cash, $comments)
    {
        $walletInfo = WalletInfo::instance($userid);
        if ($walletInfo == null) {
            return Error::instance('wallet_not_found');
        }
        if ($walletInfo->blockedCoin < $coin) {
            return Error::instance('wallet_blocked_coin_not_enough');
        }
        if ($walletInfo->blockedCash < $cash) {
            return Error::instance('wallet_blocked_cash_not_enough');
        }

        DB::beginTransaction();
        $walletInfo->coin        += $coin;
        $walletInfo->blockedCoin -= $coin;
        $walletInfo->cash        += $cash;
        $walletInfo->blockedCash -= $cash;
        $isSuccess = $walletInfo->update();

        if (!$isSuccess) {
            DB::rollBack();
            return Error::instance('wallet_update_error');
        }

        $record           = new BlockedRecordInfo();
        $record->orderid  = Utils::newSerialID();
        $record->userid   = $walletInfo->userId;
        $record->coin     = $coin;
        $record->cash     = $cash;
        $record->comments = $comments;

        $isSuccess = $record->create();
        if (!$isSuccess) {
            DB::rollBack();
            return Error::instance('wallet_update_error');
        }
        DB::commit();
        return true;
    }

    /**
     * 冻结交易
     * @param $payUserid
     * @param $payCash
     * @param $reciveUserid
     * @param $reciveCoin
     * @param $comments
     * @return Error|bool
     */
    public function blockedTrade($payUserid, $payCash, $reciveUserid, $reciveCoin, $comments)
    {
        $payWalletInfo = WalletInfo::instance($payUserid);
        if ($payWalletInfo == null) {
            return Error::instance('wallet_not_found');
        }
        if ($payWalletInfo->blockedCash < $payCash) {
            return Error::instance('wallet_blocked_cash_not_enough');
        }
        $reciveWalletInfo = WalletInfo::instance($reciveUserid);
        if ($reciveWalletInfo == null) {
            return Error::instance('wallet_not_found');
        }
        if ($reciveWalletInfo->blockedCoin < $reciveCoin) {
            return Error::instance('wallet_blocked_coin_not_enough');
        }

        DB::beginTransaction();
        $reciveWalletInfo->blockedCoin -= $reciveCoin;
        $reciveWalletInfo->blockedCash += $payCash;
        $reciveRes = $reciveWalletInfo->update();

        $payWalletInfo->blockedCoin += $reciveCoin;
        $payWalletInfo->blockedCash -= $payCash;
        $payRes = $reciveWalletInfo->update();

        if (!($reciveRes && $payRes)) {
            DB::rollBack();
            return Error::instance('wallet_update_error');
        }
        DB::commit();
        return true;
    }

    /**
     * 钱包请求地址
     * @return mixed
     */
    public function coinRechargeAddress(){
        $url = "http://coin.dry5.cn:9332";
        $param = [
            'method' => 'getnewaddress',
            'params' => null
        ];
        return $this->getParam($url,$param);
    }


    public function getParam($url, $param)
    {
        $client = new Client();
        $rs = $client->request('POST',$url,$param);
        $resp = $rs->getBody();
        $res = json_decode($resp, true);
        return $res['result'];
    }
}
