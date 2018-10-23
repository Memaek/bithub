<?php
namespace App\Service;



use App\Models\Device;
use App\Models\UserDevice;

class DeviceService {

    /**
     * 绑定钱包地址
     *
     * @param $mac
     * @param $sn
     * @param $address
     * @return Error|bool
     */
    public function setMacAddress($mac,$sn,$address)
    {
        $device = Device::where('mac',$mac)->where('sn',$sn)->first();
        if ($device == null) {
            return Error::instance('mac_not_found');
        }
        $device->address = $address;
        $bool = $device->save();
        if (!$bool) {
            return Error::instance('mac_addr_error');
        }
        return true;
    }

    /**
     * 获取钱包地址
     *
     * @param $mac
     * @return Error|array
     */
    public function getMacAddress($mac)
    {
        $device = Device::where('mac',$mac)->first();
        if ($device == null) {
            return Error::instance('mac_not_found');
        }
        return ['address'=>$device->address];
    }
}
