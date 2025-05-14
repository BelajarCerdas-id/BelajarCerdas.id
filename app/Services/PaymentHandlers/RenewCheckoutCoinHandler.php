<?php

namespace App\Services\PaymentHandlers;

use App\Models\Transactions;
use App\Models\TanyaUserCoin;

class RenewCheckoutCoinHandler
{
    public static function handle(Transactions $transaction)
    {
        $userId = $transaction->user_id;
        $jumlahKoin = $transaction->jumlah_koin;

        $tanyaUserCoin = TanyaUserCoin::where('user_id', $userId)->first();
        if($tanyaUserCoin) {
            $tanyaUserCoin->update([
                'jumlah_koin' => $tanyaUserCoin->jumlah_koin + $jumlahKoin
            ]);
        } else {
            TanyaUserCoin::create([
                'user_id' => $userId,
                'jumlah_koin' => $jumlahKoin,
            ]);
        }
    }
}
