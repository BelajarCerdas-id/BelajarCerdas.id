<?php

namespace App\Http\Controllers;

use App\Models\FeaturePrices;
use App\Models\Features;
use App\Models\Transactions;
use App\Services\MidtransService;
use App\Services\PaymentHandlers\CheckoutCoinHandler;
use App\Services\PaymentHandlers\RenewCheckoutCoinHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class PaymentFeaturesController extends Controller
{
    // Fungsi reusable untuk mendapatkan array payment methods
    public function getPaymentMethods()
    {
        return [
            [
                'id' => '1',
                'tipe_payment' => 'ATM / Bank Transfer',
                'logo-payment' => asset('image/logo-payment-method/bca.png'),
                'name' => 'BCA'
            ],
            [
                'id' => '2',
                'tipe_payment' => 'ATM / Bank Transfer',
                'logo-payment' => asset('image/logo-payment-method/BNI.png'),
                'name' => 'BNI'
            ],
            [
                'id' => '3',
                'tipe_payment' => 'ATM / Bank Transfer',
                'logo-payment' => asset('image/logo-payment-method/bri.png'),
                'name' => 'BRI'
            ],
            [
                'id' => '4',
                'tipe_payment' => 'ATM / Bank Transfer',
                'logo-payment' => asset('image/logo-payment-method/mandiri.png'),
                'name' => 'MANDIRI'
            ],
            [
                'id' => '5',
                'tipe_payment' => 'E-Wallet',
                'logo-payment' => asset('image/logo-payment-method/dana.png'),
                'name' => 'DANA'
            ],
            [
                'id' => '6',
                'tipe_payment' => 'E-Wallet',
                'logo-payment' => asset('image/logo-payment-method/gopay.png'),
                'name' => 'GOPAY'
            ],
            [
                'id' => '7',
                'tipe_payment' => 'E-Wallet',
                'logo-payment' => asset('image/logo-payment-method/ovo.png'),
                'name' => 'OVO'
            ],
            [
                'id' => '8',
                'tipe_payment' => 'E-Wallet',
                'logo-payment' => asset('image/logo-payment-method/qris.png'),
                'name' => 'QRIS'
            ],
        ];
    }

    public function paymentFeaturesView($nama_fitur)
    {
        $features = Features::all();
        $paymentMethods = $this->getPaymentMethods(); // ✅ ini return array
        $groupedPaymentMethods = collect($paymentMethods)->groupBy('tipe_payment');

        // mengambil data features prices sesuai dengan parameter nama fitur
        $dataFeaturesPrices = FeaturePrices::whereHas('Features', function ($data) use ($nama_fitur) {
            $data->where('nama_fitur', $nama_fitur);
        })->get();


        return view('Features.payment-features.pembayaran-fitur', compact(
            'nama_fitur', 'features', 'paymentMethods', 'groupedPaymentMethods', 'dataFeaturesPrices'
        ));
    }

    // FUNCTION CALLBACK MIDTRANS AFTER CHECKOUT (callback midtrans hanya ada 1 untuk semua fitur)
    public function callback(Request $request)
    {
        Log::info('Midtrans Callback Received:', $request->all());
        MidtransService::init();
        $notif = new \Midtrans\Notification();

        $orderId = $notif->order_id;
        $midtransStatus = $notif->transaction_status; // status asli dari midtrans
        // $paymentMethod = $notif->payment_type;

        $statusMap = [
            'capture' => 'Berhasil',
            'settlement' => 'Berhasil',
            'pending' => 'Pending',
            'expire' => 'Kadaluarsa',
            'failure' => 'Gagal',
            'cancel' => 'Gagal',
            'deny' => 'Gagal',
        ];

        $localStatus = $statusMap[$midtransStatus] ?? 'Pending';

        $transaction = Transactions::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan.'], 404);
        }

        $transaction->transaction_status = $localStatus;
        $transaction->save();

        $parts = explode('-', $orderId);
        $key = implode('-', array_slice($parts, 0, 3)); // contoh ['BC', 'co', 'tanya'] -> 'BC-co-tanya'

        if ($localStatus === 'Berhasil') {
            // Mapping ke handler berdasarkan feature_id
            $midtransHandlers = [
                'BC-co-tanya' => CheckoutCoinHandler::class,
                'BC-rnw-tanya' => RenewCheckoutCoinHandler::class,
            ];

            $handler = $midtransHandlers[$key] ?? null;
            if($handler) {
                $handler::handle($transaction);
            }
        }
        return response()->json(['message' => 'Callback received.']);
    }

    // FUNCTION TANYA PAYMENT
    // FUNCTION CHECKOUT COIN TANYA (purchase)
    public function checkoutCoinTanya(Request $request)
    {
        $user = Auth::user();
        $orderId = 'BC-co-tanya-' . Str::uuid();
        $paymentMethod = strtolower($request->payment_method_id);

        $paymentMap = [
            'bca' => 'bca_va',
            'bni' => 'bni_va',
            'bri' => 'bri_va',
            'mandiri' => 'echannel',
            'qris' => 'qris',
            'gopay' => 'gopay',
            'dana' => 'dana',
            'ovo' => 'ovo',
        ];

        if (!array_key_exists($paymentMethod, $paymentMap)) {
            return response()->json(['error' => 'Metode tidak dikenali.'], 400);
        }

        $transaction = Transactions::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'feature_id' => $request->feature_id ?? null,
            'payment_method' => $paymentMethod,
            'feature_variant_id' => $request->feature_variant_id ?? null,
            'price' => (int)$request->price,
            'transaction_status' => 'Pending',
            'jumlah_koin' => $request->jumlah_koin ?? 0
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int)$request->price,
            ],
            'enabled_payments' => [$paymentMap[$paymentMethod]],
            'customer_details' => [
                'first_name' => $user->Profile->nama_lengkap ?? 'Customer',
                'email' => $user->email ?? 'dummy@example.com',
            ],
        ];

        try {
            Log::info('Midtrans Params:', $params);
            Log::info('Request Data:', $request->all());
            $snap = MidtransService::createTransaction($params);

            $transaction->snap_token = $snap->token;
            $transaction->save();

            return response()->json(['snap_token' => $snap->token]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // FUNCTION RENEW CHECKOUT PENDING COIN TANYA
    public function renewCheckoutCoinTanya(Request $request, String $id)
    {
        $getDataTransactions = Transactions::find($id);
        $orderId = 'BC-rnw-tanya-' . Str::uuid(); // Generate new order_id
        $paymentMethod = $getDataTransactions->payment_method;

        // Payment method mapping
        $paymentMap = [
            'bca' => 'bca_va',
            'bni' => 'bni_va',
            'bri' => 'bri_va',
            'mandiri' => 'echannel',
            'qris' => 'qris',
            'gopay' => 'gopay',
            'dana' => 'dana',
            'ovo' => 'ovo',
        ];

        $getDataTransactions->update([
            'order_id' => $orderId,
        ]);

        // Ensure the payment method is valid
        if (!array_key_exists($paymentMethod, $paymentMap)) {
            return response()->json(['error' => 'Metode pembayaran tidak dikenali.'], 400);
        }

        // Create transaction params
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int)$getDataTransactions->price,
            ],
            'enabled_payments' => [$paymentMap[$paymentMethod]],
            'customer_details' => [
                'first_name' => $getDataTransactions->Profile->nama_lengkap ?? 'Customer',
                'email' => $getDataTransactions->email ?? 'dummy@example.com',
            ],
        ];

        try {
            // Generate Midtrans snap token
            $snap = MidtransService::createTransaction($params);

            // Update the transaction with the new snap_token
            $getDataTransactions->snap_token = $snap->token;

            $getDataTransactions->save();

            // Return snap_token to frontend
            return response()->json(['snap_token' => $snap->token]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // FUNCTION SOAL DAN PEMBAHASAN PAYMENT
    public function soalDanPembahasanPayment(Request $request, string $id)
    {

    }
}