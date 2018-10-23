<?php
namespace App\Service\User;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MakeQRCode {

    public function getQrCode($imgName)
    {
        if(!file_exists(public_path('qrcodes')))
        {
            mkdir(public_path('qrcodes'));
        }
        QrCode::encoding('UTF-8');
        QrCode::format('png')
            ->size(200)
            ->generate(env('LOCATION_HERF').'?user='.$imgName, public_path('qrcodes/'.$imgName.'.png'));
    }
}