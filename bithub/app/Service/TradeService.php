<?php

namespace App\Service;

use App\Jobs\MatchTradeOrder;
use App\Models\TradeOrder;
use App\Service\Trade\TradeMatchOrderInfo;
use App\Service\Trade\TradeOrderFinishInfo;
use App\Service\Trade\TradeOrderInfo;
use App\Service\Trade\TradeStatus;
use App\Service\Trade\TradeType;
use App\Service\Utils\Utils;
use App\Service\Wallet\WalletInfo;
use Illuminate\Support\Facades\DB;

class TradeService
{
    private $userService;
    private $walletService;

    public function __construct(UserService $userService, WalletService $walletService)
    {
        $this->userService   = $userService;
        $this->walletService = $walletService;
    }

    /**
     * 创建购买订单
     * @param $userid
     * @param $price
     * @param $target
     * @return Error|bool
     */
    public function createBuyCoinOrder($userid, $price, $target)
    {
        $user = $this->userService->getUserInfo($userid);
        if ($user == null) {
            return Error::instance('user_not_found');
        }
        $wallet = $this->walletService->getWalletInfo($userid);
        if ($wallet == null) {
            return Error::instance('wallet_not_found');
        }
        $amount = $price * $target;
        if ($wallet->cash < $amount) {
            return Error::instance('wallet_cash_not_enough');
        }

        DB::beginTransaction();

        $tradeOrderInfo              = new TradeOrderInfo();
        $tradeOrderInfo->orderId     = Utils::newSerialID();
        $tradeOrderInfo->userId      = $userid;
        $tradeOrderInfo->price       = $price;
        $tradeOrderInfo->target      = $target;
        $tradeOrderInfo->tradeStatus = TradeStatus::STATUS_NEW;
        $tradeOrderInfo->tradeType   = TradeType::TYPE_BUY;
        $tradeOrderInfo->complete    = 0;
        $tradeOrderInfo->blockedCoin = 0;
        $tradeOrderInfo->blockedCash = $amount;

        $isSuccess = $tradeOrderInfo->create();
        if (!$isSuccess) {
            DB::rollBack();
            return Error::instance('trade_order_create_error');
        }

        $comments  = json_encode(['type' => 'BUY_COIN_ORDER_CREATE', 'orderid' => $tradeOrderInfo->orderId]);
        $result = $this->walletService->blocked($userid, 0, $amount, $comments);

        if (!$result) {
            DB::rollBack();
            return Error::instance('wallet_cash_blocked_error');
        }
        DB::commit();
        return true;
    }

    /**
     * 创建卖出订单
     * @param $userid
     * @param $price
     * @param $target
     * @return Error|TradeOrderInfo
     */
    public function createSellCoinOrder($userid, $price, $target)
    {
        $user = $this->userService->getUserInfo($userid);
        if ($user == null) {
            return Error::instance('user_not_found');
        }
        $wallet = $this->walletService->getWalletInfo($userid);
        if ($wallet == null) {
            return Error::instance('wallet_not_found');
        }
        if ($wallet->coin < $target) {
            return Error::instance('wallet_cash_not_enough');
        }

        DB::beginTransaction();

        $tradeOrderInfo              = new TradeOrderInfo();
        $tradeOrderInfo->orderId     = Utils::newSerialID();
        $tradeOrderInfo->userId      = $userid;
        $tradeOrderInfo->price       = $price;
        $tradeOrderInfo->target      = $target;
        $tradeOrderInfo->tradeStatus = TradeStatus::STATUS_NEW;
        $tradeOrderInfo->tradeType   = TradeType::TYPE_SELL;
        $tradeOrderInfo->complete    = 0;
        $tradeOrderInfo->blockedCoin = $target;
        $tradeOrderInfo->blockedCash = 0;

        $isSuccess = $tradeOrderInfo->create();
        if (!$isSuccess) {
            DB::rollBack();
            return Error::instance('trade_order_create_error');
        }
        $comments = json_encode(['type' => 'SELL_COIN_ORDER_CREATE', 'orderid' => $tradeOrderInfo->orderId]);
        $result = $this->walletService->blocked($userid, $target, 0, $comments);

        if (!$result) {
            DB::rollBack();
            return Error::instance('wallet_cash_blocked_error');
        }
        DB::commit();
        return true;
    }



    /**
     * 撮合订单
     */
    public function match()
    {
        $maxPriceBuyOrder  = TradeOrderInfo::maxPriceBuyOrder();
        $minPriceSellOrder = TradeOrderInfo::minPriceSellOrder();
        if ($maxPriceBuyOrder == null && $minPriceSellOrder == null) {
            return Error::instance('match_order_not_exists');
        }
        if ($maxPriceBuyOrder->price < $minPriceSellOrder->price) {
            return Error::instance('match_order_price_error');
        }

        DB::beginTransaction();

        $tradeMatchOrderInfo              = new TradeMatchOrderInfo();
        $tradeMatchOrderInfo->orderId     = Utils::newSerialID();
        $tradeMatchOrderInfo->sellOrderId = $minPriceSellOrder->orderId;
        $tradeMatchOrderInfo->sellPrice   = $minPriceSellOrder->price;
        $tradeMatchOrderInfo->buyOrderId  = $maxPriceBuyOrder->orderId;
        $tradeMatchOrderInfo->buyPrice    = $maxPriceBuyOrder->price;
        $tradeMatchOrderInfo->matchPrice  = ($minPriceSellOrder->price + $maxPriceBuyOrder->price) / 2;

        $sellCount = $minPriceSellOrder->target - $minPriceSellOrder->complete;
        $buyCount  = $maxPriceBuyOrder->target  - $maxPriceBuyOrder->complete;
        if ($sellCount > $buyCount) {
            $tradeMatchOrderInfo->matchCount = $sellCount;
            $minPriceSellOrder->complete    += $buyCount;
            $maxPriceBuyOrder->complete     += $buyCount;
        } else {
            $tradeMatchOrderInfo->matchCount = $buyCount;
            $minPriceSellOrder->complete    += $sellCount;
            $maxPriceBuyOrder->complete     += $sellCount;
        }
        $isSuccess = $tradeMatchOrderInfo->create();
        if (!$isSuccess) {
            DB::rollBack();
            // 撮合交易订单出错
            return Error::instance('match_order_error');
        }
        //更新 买单、卖单 blockedcoin blockedcash complete以及交易状态
        $maxPriceBuyOrder ->blockedCash = $tradeMatchOrderInfo->matchCount * $maxPriceBuyOrder->price;
        $maxPriceBuyOrder ->tradeStatus = 1;

        $minPriceSellOrder->blockedCoin = $tradeMatchOrderInfo->matchCount;
        $minPriceSellOrder->tradeStatus = 1;

        //更新 买家、卖家钱包资产信息 blockedcoin blockedcash
        $buyUser               = WalletInfo::instance($maxPriceBuyOrder->userId);
        $buyUser->blockedCash += $maxPriceBuyOrder->blockedCash;
        $buyUser->cash        -= $maxPriceBuyOrder->blockedCash;

        $sellUser               = WalletInfo::instance($minPriceSellOrder->userId);
        $sellUser->blockedCoin += $minPriceSellOrder->blockedCoin;
        $sellUser->coin        -= $minPriceSellOrder->blockedCoin;

        if (!($maxPriceBuyOrder->update() && $minPriceSellOrder->update()
            && $buyUser->update() && $sellUser->update())) {
            DB::rollBack();
            // 撮合交易订单出错
            return Error::instance('trade_order_update_error');
        }
        DB::commit();
        return true;
    }

    /**
     * 取消订单
     */
    public function cancel($orderid)
    {
        $tradeOrderInfo = TradeOrderInfo::instance($orderid);
        if (is_null($tradeOrderInfo)) {
            return Error::instance('trade_order_not_found');
        }

        DB::beginTransaction();

        $tradeOrderInfo->tradeStatus = 2;
        $tradeOrderFinishInfo = TradeOrderFinishInfo::instanceWithArray($tradeOrderInfo->toArray());
        $isSuccess            = $tradeOrderFinishInfo->create();
        if (!($isSuccess)) {
            DB::rollBack();
            return Error::instance('trade_finish_order_create_error');
        }

        $isSuccess = $tradeOrderInfo->delete();
        if (!$isSuccess) {
            DB::rollBack();
            return Error::instance('trade_order_delete_error');
        }

        $comments  = json_encode(['type' => 'TRADE_ORDER_CANCEL', 'orderid' => $tradeOrderInfo->orderId]);
        $isSuccess = $this->walletService->unblocked(
            $tradeOrderInfo->userId,
            $tradeOrderInfo->blockedCoin,
            $tradeOrderInfo->blockedCash,
            $comments
        );
        if (!$isSuccess) {
            DB::rollBack();
            return Error::instance('wallet_cash_blocked_error');
        }
        DB::commit();
        return true;
    }

    /**
     * 获取用户订单列表
     */
    public function getlist($userid, $page,$ordertype)
    {
        $data = TradeOrderInfo::orderList($userid,$page,$ordertype);
        return $data;
    }

    public function testUser($userid)
    {
        return $this->neworderid() . '_' . $this->userService->getUserInfo($userid)->username;
    }

    public function neworderid()
    {
        return Utils::newSerialID();
    }
}