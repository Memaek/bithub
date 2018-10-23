<?php

namespace App\Service\Trade;


use App\Models\TradeOrderFinish;

class TradeOrderFinishInfo
{
    public $orderId;
    public $userId;
    public $price;
    public $target;
    public $complete;
    public $tradeStatus;
    public $tradeType;
    public $updateDatetime;
    public $createDatetime;

    public function toArray()
    {
        return [
            'order_id'        => $this->orderId,
            'user_id'         => $this->userId,
            'price'           => $this->price,
            'target'          => $this->target,
            'complete'        => $this->complete,
            'trade_status'    => $this->tradeStatus,
            'trade_type'      => $this->tradeType,
            'create_datetime' => $this->createDatetime,
            'update_datetime' => $this->updateDatetime
        ];
    }

    public function update()
    {
        // todo:字段校验
        return TradeOrderFinish::where('order_id', $this->orderId)
                                ->where('update_datetime', $this->updateDatetime)
                                ->update(['trade_status' => $this->tradeStatus]);
    }

    public function create()
    {
        // todo:字段校验
        $model =  TradeOrderFinish::create(array_slice($this->toArray(),0,count($this->toArray())-2,true));
        return $model instanceof TradeOrderFinish;
    }

    public static function instanceWithArray($order)
    {
        $info                 = new TradeOrderFinishInfo();
        $info->orderId        = $order['order_id'];
        $info->userId         = $order['user_id'];
        $info->price          = $order['price'];
        $info->target         = $order['target'];
        $info->complete       = $order['complete'];
        $info->tradeStatus    = $order['trade_status'];
        $info->tradeType      = $order['trade_type'];
        $info->createDatetime = $order['create_datetime'];
        $info->updateDatetime = $order['update_datetime'];
        return $info;
    }

    /**
     * @param $orderid
     *
     * @return TradeOrderFinishInfo|null
     */
    public static function instance($orderid)
    {
        $model = TradeOrderFinish::where('order_id', $orderid)->first();
        if ($model == null) {
            return null;
        }
        $info                 = new TradeOrderFinishInfo();
        $info->orderId        = $model->order_id;
        $info->userId         = $model->user_id;
        $info->price          = $model->price;
        $info->target         = $model->target;
        $info->complete       = $model->complete;
        $info->tradeStatus    = $model->trade_status;
        $info->tradeType      = $model->trade_type;
        $dataTime             = TradeOrderFinish::setDatetimeByOrderId($info->orderId);
        $info->createDatetime = $dataTime['create_time'];
        $info->updateDatetime = $dataTime['update_time'];
        return $info;
    }
}