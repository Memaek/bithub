<?php
/**
 * Created by PhpStorm.
 * User: jie
 * Date: 2018/8/24
 * Time: 11:57
 */

namespace App\Service\Wallet;



use App\Models\Bank;

class BankInfo
{
    public $id;
    public $mark;
    public $userId;
    public $number;

    public function toArray()
    {
        return [
            'id'      => $this->id,
            'mark'    => $this->mark,
            'user_id' => $this->userId,
            'number'  => $this->number,
        ];
    }

    public function create()
    {
        $bank = Bank::create($this->toArray());
        return $bank instanceof Bank;
    }

    public function delete()
    {
        return Bank::where('id', $this->id)->delete();
    }

    public function update()
    {
        return Bank::where('id', $this->id)->update(
            [
                'number' => $this->number,
                'mark' => $this->mark
            ]);
    }

    public function selectAllData($userid)
    {
        return Bank::where('user_id',$userid)->get();
    }

    public  function selectByUidAnNumber($userid,$number)
    {
        $bank = Bank::where('user_id',$userid)->where('number',$number)->first();
        if($bank == null){
            return null;
        }
        $bankInfo         = new BankInfo();
        $bankInfo->id     = $bank->id;
        $bankInfo->mark   = $bank->mark;
        $bankInfo->userId = $bank->user_id;
        $bankInfo->number = $bank->number;
        return $bankInfo;
    }

    public function selectById($id)
    {
        $bank = Bank::where('id',$id)->first();
        if($bank == null){
            return null;
        }
        $bankInfo         = new BankInfo();
        $bankInfo->id     = $bank->id;
        $bankInfo->mark   = $bank->mark;
        $bankInfo->userId = $bank->user_id;
        $bankInfo->number = $bank->number;
        return $bankInfo;
    }

    public static function instance($userid)
    {
        $bank = Bank::where('user_id',$userid)->first();
        if($bank == null){
            return null;
        }
        $bankInfo         = new BankInfo();
        $bankInfo->id     = $bank->id;
        $bankInfo->mark   = $bank->mark;
        $bankInfo->userId = $bank->user_id;
        $bankInfo->number = $bank->number;
        return $bankInfo;
    }
}