<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $connection = 'bithub';
    protected $fillable = ['username','password','reg_ip','reg_date'];
    public $timestamps = false;
}
