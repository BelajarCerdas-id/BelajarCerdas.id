<?php

namespace App\Console\Commands;

use App\Models\CoinHistory;
use App\Models\TanyaUserCoin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CoinExpireDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Koin harian tanya telah kadaluarsa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now();

        // Cari semua klaim koin yang dibuat lebih dari 1 menit lalu
        $expiredCoinDaily = CoinHistory::where('created_at', '<', $today->subMinutes(1))->get();

        // Ambil saldo koin user dari TanyaUserCoin berdasarkan user_id yang ada di expiredCoinDaily
        $userIds = $expiredCoinDaily->pluck('user_id')->unique();
        $tanyaUserCoins = TanyaUserCoin::whereIn('user_id', $userIds)->get()->keyBy('user_id');

        foreach ($expiredCoinDaily as $userCoin) {
            $userId = $userCoin->user_id;
            $claimedAt = $userCoin->created_at;

            $claimedAmount = CoinHistory::where('user_id', $userId)
                ->where('sumber_koin', 'Koin Harian')
                ->where('created_at', $claimedAt)
                ->value('jumlah_koin') ?? 0;

            $used = CoinHistory::where('user_id', $userId)
                ->where('tipe_koin', 'Keluar')
                ->where('created_at', '>=', $claimedAt)
                ->where('created_at', '<', $today)
                ->sum('jumlah_koin');

            $unused = max(0, $claimedAmount - $used);

            // Dapatkan saldo koin user dari TanyaUserCoin
            $userBalance = $tanyaUserCoins->get($userId);

            if (!$userBalance) {
                Log::warning("User $userId tidak memiliki saldo di TanyaUserCoin.");
                continue; // lanjut ke user berikutnya
            }

            if ($unused > 0 && $userBalance->jumlah_koin >= $unused) {
                $userBalance->jumlah_koin = $userBalance->jumlah_koin - $unused;
                $userBalance->save();

                Log::info("User $userId: $unused koin harian hangus karena tidak dipakai dalam 5 menit.");
                $this->info("User $userId: $unused koin harian hangus karena tidak dipakai dalam 5 menit.");
            } else {
                Log::info("User $userId: Tidak ada koin yang hangus.");
            }
        }
    }
}