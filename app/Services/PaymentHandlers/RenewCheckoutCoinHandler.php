<?php

namespace App\Services\PaymentHandlers;

use App\Models\CoinHistory;
use App\Models\Transactions;
use App\Models\TanyaUserCoin;

class RenewCheckoutCoinHandler
{
    public static function handle(Transactions $transaction)
    {
        $userId = $transaction->user_id;
        $transactionCallback = $transaction->transaction_callback;

        $tanyaUserCoin = TanyaUserCoin::where('user_id', $userId)->first();

        // menambahkan koin untuk user yang melakukan pembayaran
        if($tanyaUserCoin) {
            $tanyaUserCoin->update([
                'jumlah_koin' => $tanyaUserCoin->jumlah_koin + $transactionCallback['jumlah_koin'],
            ]);
        } else {
            TanyaUserCoin::create([
                'user_id' => $userId,
                'jumlah_koin' => $transactionCallback['jumlah_koin'],
            ]);
        }

        // riwayat koin masuk
        CoinHistory::create([
            'user_id' => $userId,
            'jumlah_koin' => $transactionCallback['jumlah_koin'],
            'tipe_koin' => 'Masuk',
            'sumber_koin' => 'Pembelian Koin',
        ]);
    }
}