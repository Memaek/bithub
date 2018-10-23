<?php

namespace App\Service\Wallet;


use App\Models\CoinTradeRecord;
use App\Models\WalletBlockedRecord;

class CoinTradeRecordInfo
{
    public $id;
    public $payUserid;
    public $receiptUserid;
    public $price;
    public $createDatetime;
    public $comments;

    public function toArray()
    {
        return [
            'id'              => $this->id,
            'pay_userid'      => $this->payUserid,
            'receipt_userid'  => $this->receiptUserid,
            'price'           => $this->price,
            'comments'        => $this->comments,
            'create_datetime' => $this->createDatetime,
        ];
    }

  public function create()
    {
        $coinTrade                 = new CoinTradeRecord();
        $coinTrade->pay_userid     = $this->payUserid;
        $coinTrade->receipt_userid = $this->receiptUserid;
        $coinTrade->price          = $this->price;
        $coinTrade->comments       = $this->comments;

        return $coinTrade->save();
    }


    public static function instance($id)
    {
        $model = CoinTradeRecord::where('id', $id)->first();
        if ($model == null) {
            return null;
        }
        $info                 = new BlockedRecordInfo();
        $info->order_id       = $model->order_id;
        $info->userid         = $model->user_id;
        $info->coin           = $model->coin;
        $info->cash           = $model->cash;
        $info->comments       = $model->comments;
        $info->createDatetime = $model->create_datetime;
        $info->updateDatetime = $model->update_datetime;
        return $info;
    }

    public function selectRecordList($userId)
    {
        $data = CoinTradeRecord::where('pay_user_id', $userId)
            ->orwhere('receipt_user_id', $userId)
            ->orderBy('create_datetime', 'desc')
            ->get();
        return $data;
        /*$record = TradeRecord::where('pay_user_id', $userId)
                            ->orwhere('receipt_user_id', $userId)
                            ->orderBy('create_datetime', 'desc')
                            ->get();*/
    }
}