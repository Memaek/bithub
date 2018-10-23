<?php

namespace App\Http\Controllers\Api;

use App\Models\DeviceAppConfig;
use App\Service\DeviceService;
use App\Service\Error;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeviceController extends Controller
{
    private $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    /**
     * 绑定钱包地址
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAddress(Request $request)
    {
        if ($request->has('mac', 'sn','address')) {
            $data = $this->deviceService->setMacAddress(
                strtoupper($request->input('mac')),
                $request->input('sn'),
                $request->input('address')
            );
            if ($data instanceof Error) {
                return  $this->responseFailed($data->toArray());
            }
            return $this->responseSuccess();
        } else {
            return $this->responseFailed(config('error.params_error'));
        }
    }

    /**
     * 获取钱包地址
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAddress(Request $request)
    {
        if (!($request->has('mac'))) {
            return $this->responseFailed(config('error.params_error'));
        }
        $data = $this->deviceService->getMacAddress(
            strtoupper($request->input('mac'))
        );
        if ($data instanceof Error) {
            return  $this->responseFailed($data->toArray());
        }
        return $this->responseSuccess($data);
    }


    public function getVersion(Request $request)
    {
        $configs = DeviceAppConfig::get();
        $data = [];
        foreach ($configs as $config) {
            $data[$config->config_key] = $config->config_value;
        }
        return $this->responseSuccess($data);
    }

    public function setVersion(Request $request)
    {
        $app_ver = $request->input('app_ver');
        $app_url = $request->input('app_url');
        $miner_ver = $request->input('miner_ver');
        $miner_url = $request->input('miner_url');
        $data = [
            'app_ver'   => $app_ver,
            'app_url'   => $app_url,
            'miner_ver' => $miner_ver,
            'miner_url' => $miner_url,
        ];
        foreach ($data as $key => $value) {
            $config = DeviceAppConfig::find($key);
            if (!$config) {
                $config = new DeviceAppConfig();
                $config->config_key = $key;
            }
            $config->config_value = $value;
            $config->save();
        }
        return $this->responseSuccess($data);
    }
}
