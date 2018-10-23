<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinTradeRecord extends Model
{
    protected $table = 'coin_trade_record';
    protected $primaryKey = 'id';
    protected $connection = 'bithub';
    public $timestamps = false;
}
