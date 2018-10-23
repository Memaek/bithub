<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'bank';
    protected $primaryKey = 'id';
    protected $connection = 'bithub';
    protected $guarded = ['id'];
    public $timestamps = false;
}
