<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletBlockedRecord extends Model
{
    protected $table = 'user_wallet_blocked_record';
    protected $primaryKey = 'id';
    protected $connection = 'bithub';
    protected $guarded = ['id'];
    public $timestamps = false;
}
