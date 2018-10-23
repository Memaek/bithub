<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'device';
    protected $primaryKey = 'mac';
    protected $connection = 'bithub';
    public $timestamps = false;

}
