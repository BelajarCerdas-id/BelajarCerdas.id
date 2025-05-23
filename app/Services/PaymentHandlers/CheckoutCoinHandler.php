<?php

namespace App\Services\PaymentHandlers;

use App\Models\CoinHistory;
use App\Models\Transactions;
use App\Models\TanyaUserCoin;

class CheckoutCoinHandler
{
    public static function handle(Transactions $transaction)
    {
        $userId = $transaction->user_id;
        $jumlahKoin = $transaction->jumlah_koin;

        $tanyaUserCoin = TanyaUserCoin::where('user_id', $userId)->first();
        $coinHistory = CoinHistory::where('user_id', $userId)->first();

        // menambahkan koin untuk user yang melakukan pembayaran
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

        // riwayat koin masuk
        CoinHistory::create([
            'user_id' => $userId,
            'jumlah_koin' => $jumlahKoin,
            'tipe_koin' => 'Masuk',
            'sumber_koin' => 'Pembelian Koin',
        ]);
    }
}