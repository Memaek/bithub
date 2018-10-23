<?php
/**
 * Created by PhpStorm.
 * User: jie
 * Date: 2018/8/24
 * Time: 14:26
 */

namespace App\Service\Wallet;


use App\Models\CashOrder;

class CashOrderInfo
{
    public $id;
    public $orderId;
    public $userId;
    public $tradeType;
    public $orderCash;
    public $bankCard;
    public $mark;
    public $status;
    public $createDatetime;
    public $updatedDatetime;

    public function toArray()
    {
        return [
            'id'        => $this->id,
            'order_id'  => $this->orderId,
            'user_id'   => $this->userId,
            'trade_type'=> $this->tradeType,
            'order_cash'=> $this->orderCash,
            'bank_card' => $this->bankCard,
            'mark'      => $this->mark,
            'status'    => $this->status,
            'create_datetime' => $this->createDatetime,
            'update_datetime' => $this->updatedDatetime
        ];
    }

    public function create()
    {
        $cashOrder = CashOrder::create(array_slice($this->toArray(),0,count($this->toArray())-2,true));
        return $cashOrder instanceof CashOrder;
    }

    public function selectFileds($userId)
    {
        $data = CashOrder::where('user_id',$userId)
            ->orderBy('created_datetime','desc')
            ->get(['id', 'order_id','trade_type','order_cash','bank_card','mark','status','created_datetime']);
       return $data;
    }
}