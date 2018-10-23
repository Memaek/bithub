<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TradeOrder extends Model
{
    protected $table = 'trade_order';
    protected $primaryKey = 'order_id';
    protected $keyType = 'string';
    protected $connection = 'bithub';
    protected $guarded = ['orderid'];
    public $timestamps = false;


    public static function setDatetimeByOrderId($orderid)
    {
        $dateTime = TradeOrder::select(DB::raw("DATE_FORMAT(create_datetime, '%Y-%m-%d %H:%i:%s.%f') as create_datetime"),
                                     DB::raw("DATE_FORMAT(update_datetime, '%Y-%m-%d %H:%i:%s.%f') as update_datetime"))
            ->where('order_id', $orderid)
            ->first();
        return array(
            'create_time' =>$dateTime->create_datetime,
            'update_time' =>$dateTime->update_datetime,
        );
    }
}
