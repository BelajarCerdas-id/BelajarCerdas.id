<?php

namespace App\Console\Commands;

use App\Events\TransactionExpired;
use App\Models\Transactions;
use Illuminate\Console\Command;

class UpdateExpiredTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update transaksi pending yang sudah melewati batas waktu menjadi kadaluarsa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now();

        // expired status
        $pendingStatus = Transactions::where('transaction_status', 'Pending')->get();

        $expiredStatus = $pendingStatus->filter(function ($transaction) use ($today) {
            return $transaction->created_at->addDays(3) < $today;
        });

        // $expiredStatus = $pendingStatus->filter(function ($transaction) use ($today) {
        //     return $transaction->created_at->diffInMinutes($today) >= 1;
        // });


        foreach ($expiredStatus as $transaction) {
            $transaction->update([
                'transaction_status' => 'Kadaluarsa'
            ]);
            broadcast(new TransactionExpired($transaction))->toOthers();
        }

        $this->info('Transaksi kadaluarsa berhasil diupdate.');
    }
}