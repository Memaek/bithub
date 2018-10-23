<?php

namespace App\Service\Wallet;


use App\Models\WalletBlockedRecord;

class BlockedRecordInfo
{
    public $id;
    public $orderid;
    public $userid;
    public $coin;
    public $cash;
    public $comments;
    public $createDatetime;

    public function toArray()
    {
        return [
            'order_id'        => $this->orderid,
            'user_id'         => $this->userid,
            'coin'            => $this->coin,
            'cash'            => $this->cash,
            'comments'        => $this->comments,
            'create_datetime' => $this->createDatetime
        ];
    }

  public function create()
    {
        $model = WalletBlockedRecord::create(array_slice($this->toArray(),0,count($this->toArray())-1,true));
        return $model->save();
    }

    public static function instance($order_id)
    {
        $model = WalletBlockedRecord::where('order_id', $order_id)->first();
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
        return $info;
    }
}