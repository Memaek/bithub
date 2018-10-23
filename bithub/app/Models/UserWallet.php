<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserWallet extends Model
{
    protected $table = 'user_wallet';
    protected $connection = 'bithub';
    protected $fillable = ['user_id'];
    public $timestamps = false;

    public static function setUpdateDateAttribute($user_id)
    {
        $update = UserWallet::select(DB::raw("DATE_FORMAT(update_datetime, '%Y-%m-%d %H:%i:%s.%f') as update_datetime"))
            ->where('user_id',$user_id)->get();
        return $update ? $update[0]->update_datetime : null;
    }
}
