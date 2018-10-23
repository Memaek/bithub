<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceAppConfig extends Model
{
    protected $table = 'device_app_config';
    protected $primaryKey = 'config_key';
    protected $keyType = 'string';
    protected $connection = 'bithub';
    public $timestamps = false;
}
