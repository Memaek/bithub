<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemMessage extends Model
{
    protected $table = 'systemmessage';
    protected $primaryKey = 'id';
    protected $connection = 'bithub';
    protected $guarded = ['id'];
    public $timestamps = false;
}
