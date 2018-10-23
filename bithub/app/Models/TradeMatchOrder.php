<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeMatchOrder extends Model
{
    protected $table = 'trade_match_order';
    protected $primaryKey = 'order_id';
    protected $keyType = 'string';
    protected $connection = 'bithub';
    protected  $fillable = ['order_id','buy_order_id','sell_order_id','buy_price','sell_price','match_count','match_price'];
    public $timestamps = false;


    public static function setCreateDateAttribute($orderid)
    {
        $create = TradeMatchOrder::select(DB::raw("DATE_FORMAT(create_datetime, '%Y-%m-%d %H:%i:%s.%f') as create_datetime"))
            ->where('order_id', $orderid)
            ->first();
        return $create ? $create->create_datetime : null;
    }
}
