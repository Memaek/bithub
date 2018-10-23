<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashOrder extends Model
{
    protected $table = 'cash_order';
    protected $primaryKey = 'id';
    protected $connection = 'bithub';
    protected $guarded = ['id','status'];
    public $timestamps = false;
}
