<?php

namespace App\Service\Utils;


use App\Service\Error;
use Illuminate\Support\Str;

class Utils
{
    public static function newSerialID ()
    {
        $serialid = strtoupper(str_replace('-', '', Str::orderedUuid()));
        return $serialid;
    }

    public static function isError($instance)
    {
        return $instance instanceof Error;
    }
}