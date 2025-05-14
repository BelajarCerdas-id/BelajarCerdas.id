<?php
namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\CoreApi;

class MidtransService
{
    // public static function init()
    // {
    //     Config::$serverKey = config('midtrans.server_key');
    //     Config::$isProduction = config('midtrans.is_production', false);
    //     Config::$isSanitized = true;
    //     Config::$is3ds = true;
    // }

    public static function init()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

        public static function createTransaction(array $params)
        {
            self::init();

            try {
                return Snap::createTransaction($params);
            } catch (\Exception $e) {
                // Log error / throw ke controller
                throw $e;
            }
        }

    // public static function createSnapToken(array $params)
    // {
    //     self::init();
    //     return Snap::getSnapToken($params);
    // }
}