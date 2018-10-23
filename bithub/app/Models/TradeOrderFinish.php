<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeOrderFinish extends Model
{
    protected $table = 'trade_order_finish';
    protected $primaryKey = 'order_id';
    protected $keyType = 'string';
    protected $connection = 'bithub';
    protected $fillable = ['order_id','user_id', 'price', 'target', 'complete', 'trade_status', 'trade_type'];
    public $timestamps = false;

    public static function setDatetimeByOrderId($orderid)
    {
        $dateTime = TradeOrderFinish::select(DB::raw("DATE_FORMAT(create_datetime, '%Y-%m-%d %H:%i:%s.%f') as create_datetime"),
            DB::raw("DATE_FORMAT(update_datetime, '%Y-%m-%d %H:%i:%s.%f') as update_datetime"))
            ->where('order_id', $orderid)
            ->first();
        return array(
            'create_time' =>$dateTime->create_datetime,
            'update_time' =>$dateTime->update_datetime,
        );
    }
}
