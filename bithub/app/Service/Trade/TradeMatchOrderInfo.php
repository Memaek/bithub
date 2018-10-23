<?php

namespace App\Service\Trade;


use App\Models\TradeMatchOrder;

class TradeMatchOrderInfo
{
    public $orderId;
    public $buyOrderId;
    public $buyPrice;
    public $sellOrderId;
    public $sellPrice;
    public $matchCount;
    public $matchPrice;
    public $createDatetime;

    public function toArray()
    {
        return [
            'order_id'        => $this->orderId,
            'buy_order_id'    => $this->buyOrderId,
            'buy_price'       => $this->buyPrice,
            'sell_order_id'   => $this->sellOrderId,
            'sell_price'      => $this->sellPrice,
            'match_count'     => $this->matchCount,
            'match_price'     => $this->matchPrice,
            'create_datetime' => $this->createDatetime
        ];
    }

    public function create()
    {
        $model = TradeMatchOrder::create($this->toArray());
        return $model instanceof TradeMatchOrder;
    }


    public static function instanceWithArray($order)
    {
        $info                 = new TradeMatchOrderInfo();
        $info->orderId        = $order['order_id'];
        $info->buyOrderId     = $order['buy_order_id'];
        $info->buyPrice       = $order['buy_price'];
        $info->sellOrderId    = $order['sell_order_id'];
        $info->sellPrice      = $order['sell_price'];
        $info->matchCount     = $order['match_count'];
        $info->matchPrice     = $order['match_price'];
        $info->createDatetime = $order['create_datetime'];
        return $info;
    }

    /**
     * @param $orderid
     *
     * @return TradeMatchOrderInfo|null
     */
    public static function instance($orderid)
    {
        $model = TradeMatchOrder::where('order_id', $orderid)->first();
        if ($model == null) {
            return null;
        }
        $info                 = new TradeMatchOrderInfo();
        $info->orderId        = $model->order_id;
        $info->buyOrderId     = $model->buy_order_id;
        $info->buyPrice       = $model->buy_price;
        $info->sellOrderId    = $model->sell_order_id;
        $info->sellPrice      = $model->sell_price;
        $info->matchCount     = $model->match_count;
        $info->matchPrice     = $model->match_price;
        $info->createDatetime = TradeMatchOrder::setCreateDateAttribute($orderid);
        return $info;
    }
}