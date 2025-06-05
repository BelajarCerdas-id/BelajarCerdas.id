<?php

use App\Console\Commands\CoinExpireDaily;
use App\Console\Commands\UpdateExpiredTransactions;
use App\Models\Transactions;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// schedule command update expired transactions
Schedule::command(UpdateExpiredTransactions::class)->everyMinute();
Schedule::command(CoinExpireDaily::class)->everyMinute();