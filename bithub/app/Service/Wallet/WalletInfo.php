<?php
/**
 * Created by PhpStorm.
 * User: jie
 * Date: 2018/8/6
 * Time: 15:50
 */

namespace App\Service\Wallet;


use App\Models\UserWallet;

class WalletInfo
{
    public $userId;
    public $coin;
    public $blockedCoin;
    public $cash;
    public $blockedCash;
    public $updateDatetime;
    public $createDatetime;

    public function toArray()
    {
        return [
            'user_id'         => $this->userId,
            'coin'            => $this->coin,
            'blocked_coin'    => $this->blockedCoin,
            'cash'            => $this->cash,
            'blocked_cash'    => $this->blockedCash,
            'create_datetime' => $this->createDatetime,
            'update_datetime' => $this->updateDatetime,
        ];
    }

    public function update()
    {
        return UserWallet::where('user_id', $this->userId)
                         ->where('update_datetime', $this->updateDatetime)
                         ->update([
                               'coin'         => $this->coin,
                               'blocked_coin' => $this->blockedCoin,
                               'cash'         => $this->cash,
                               'blocked_cash' => $this->blockedCash]
                         );
    }

    public function create()
    {
        $userWallet = UserWallet::create(array_slice($this->toArray(),0,count($this->toArray())-2),true);
        return $userWallet instanceof UserWallet;
    }

    /**
     * @param $userid
     *
     * @return WalletInfo|null
     */
    public static function instance($userid)
    {
        $userWallet = UserWallet::where('user_id', $userid)->first();
        if ($userWallet == null) {
            return null;
        }
        $walletInfo                 = new WalletInfo();
        $walletInfo->userId         = $userWallet->user_id;
        $walletInfo->coin           = $userWallet->coin;
        $walletInfo->blockedCoin    = $userWallet->blocked_coin;
        $walletInfo->cash           = $userWallet->cash;
        $walletInfo->blockedCash    = $userWallet->blocked_cash;
        $walletInfo->createDatetime = $userWallet->create_datetime;
        $walletInfo->updateDatetime = UserWallet::setUpdateDateAttribute($userid);
        return $walletInfo;
    }
}