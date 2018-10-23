<?php

namespace App\Service\Trade;


use App\Models\TradeOrder;

class TradeOrderInfo
{
    public $orderId;
    public $userId;
    public $price;
    public $target;
    public $complete;
    public $blockedCoin;
    public $blockedCash;
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
            'trade_status'    => $this->tradeStatus,
            'trade_type'      => $this->tradeType,
            'complete'        => $this->complete,
            'blocked_coin'    => $this->blockedCoin,
            'blocked_cash'    => $this->blockedCash,
            'create_datetime' => $this->createDatetime,
            'update_datetime' => $this->updateDatetime
        ];
    }

    public function update()
    {
        return TradeOrder::where('order_id', $this->orderId)
                         ->where('update_datetime', $this->updateDatetime)
                         ->update([
                                      'trade_status' => $this->tradeStatus,
                                      'complete'     => $this->complete,
                                      'blocked_coin' => $this->blockedCoin,
                                      'blocked_cash' => $this->blockedCash,
                                  ]);
    }

    public function delete()
    {
        return TradeOrder::where('order_id', $this->orderId)
                         ->where('update_datetime', $this->updateDatetime)
                         ->delete();
    }

    public function create()
    {
        $model = TradeOrder::create(array_slice($this->toArray(),0,count($this->toArray())-2,true));
        return $model instanceof TradeOrder;
    }

    public static function orderList($userId,$page=1,$ordertype=null,$limit=10)
    {
        $data = TradeOrder::where('user_id',$userId)
                ->offset(($page-1)*$limit)
                ->limit($limit)
                ->get(['order_id','price','trade_type','update_datetime','trade_status']);

        if($ordertype == 'buy'){
            $data = TradeOrder::where('user_id',$userId)
                ->where('trade_type',$ordertype)
                ->offset(($page-1)*$limit)
                ->limit($limit)
                ->get(['order_id','price','trade_type','update_datetime','trade_status']);
        }

        if($ordertype == 'sell'){
            $data = TradeOrder::where('user_id',$userId)
                ->where('trade_type',$ordertype)
                ->offset(($page-1)*$limit)
                ->limit($limit)
                ->get(['order_id','price','trade_type','update_datetime','trade_status']);
        }
        return $data;
    }

    public static function instanceWithArray($order)
    {
        $info                 = new TradeOrderInfo();
        $info->orderId        = $order['order_id'];
        $info->userId         = $order['user_id'];
        $info->price          = $order['price'];
        $info->target         = $order['target'];
        $info->complete       = $order['complete'];
        $info->blockedCoin    = $order['blocked_coin'];
        $info->blockedCash    = $order['blocked_cash'];
        $info->tradeStatus    = $order['trade_status'];
        $info->tradeType      = $order['trade_type'];
        $info->createDatetime = $order['create_datetime'];
        $info->updateDatetime = $order['update_datetime'];
        return $info;
    }

    /**
     * @param $orderid
     *
     * @return TradeOrderInfo|null
     */
    public static function instance($orderid)
    {
        $model = TradeOrder::where('order_id', $orderid)->first();
        if ($model == null) {
            return null;
        }
        $info                 = new TradeOrderInfo();
        $info->orderId        = $model->order_id;
        $info->userId         = $model->user_id;
        $info->price          = $model->price;
        $info->target         = $model->target;
        $info->complete       = $model->complete;
        $info->blockedCoin    = $model->blocked_coin;
        $info->blockedCash    = $model->blocked_cash;
        $info->tradeStatus    = $model->trade_status;
        $info->tradeType      = $model->trade_type;
        $dataTime             = TradeOrder::setDatetimeByOrderId($model->order_id);
        $info->createDatetime = $dataTime['create_time'];
        $info->updateDatetime = $dataTime['update_time'];
        return $info;
    }

    public static function maxPriceBuyOrder()
    {
        $model = TradeOrder::where('trade_type', TradeType::TYPE_BUY)
                           ->whereColumn('target', '>', 'complete')
                           ->orderBy('price', 'desc')
                           ->orderBy('create_datetime', 'asc')
                           ->first();
        if ($model == null) {
            return null;
        }
        $info                 = new TradeOrderInfo();
        $info->orderId        = $model->order_id;
        $info->userId         = $model->user_id;
        $info->price          = $model->price;
        $info->target         = $model->target;
        $info->complete       = $model->complete;
        $info->blockedCoin    = $model->blocked_coin;
        $info->blockedCash    = $model->blocked_cash;
        $info->tradeStatus    = $model->trade_status;
        $info->tradeType      = $model->trade_type;
        $dataTime             = TradeOrder::setDatetimeByOrderId($model->order_id);
        $info->createDatetime = $dataTime['create_time'];
        $info->updateDatetime = $dataTime['update_time'];
        return $info;
    }

    public static function minPriceSellOrder()
    {
        $model = TradeOrder::where('trade_type', TradeType::TYPE_SELL)
                           ->whereColumn('target', '>', 'complete')
                           ->orderBy('price', 'asc')
                           ->orderBy('create_datetime', 'asc')
                           ->first();
        if ($model == null) {
            return null;
        }
        $info                 = new TradeOrderInfo();
        $info->orderId        = $model->order_id;
        $info->userId         = $model->user_id;
        $info->price          = $model->price;
        $info->target         = $model->target;
        $info->complete       = $model->complete;
        $info->blockedCoin    = $model->blocked_coin;
        $info->blockedCash    = $model->blocked_cash;
        $info->tradeStatus    = $model->trade_status;
        $info->tradeType      = $model->trade_type;
        $dataTime             = TradeOrder::setDatetimeByOrderId($model->order_id);
        $info->createDatetime = $dataTime['create_time'];
        $info->updateDatetime = $dataTime['update_time'];
        return $info;
    }
}