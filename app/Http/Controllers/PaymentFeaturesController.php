<?php

namespace App\Http\Controllers;

use App\Models\FeaturePrices;
use App\Models\Features;
use App\Models\FeatureSubscriptionHistory;
use App\Models\Transactions;
use App\Services\MidtransService;
use App\Services\PaymentHandlers\CheckoutCoinHandler;
use App\Services\PaymentHandlers\CheckoutSoalPembahasanSubscriptionHandler;
use App\Services\PaymentHandlers\RenewCheckoutCoinHandler;
use App\Services\PaymentHandlers\RenewCheckoutSoalPembahasanSubscriptionHandler;
use Carbon\Carbon;
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
        // Ambil tanggal hari ini
        $today = Carbon::now()->format('Y-m-d');

        $userId = Auth::id();

        $features = Features::all();
        $paymentMethods = $this->getPaymentMethods(); // ✅ ini return array
        $groupedPaymentMethods = collect($paymentMethods)->groupBy('tipe_payment');

        // mengambil data features prices sesuai dengan parameter nama fitur
        $dataFeaturesPrices = FeaturePrices::whereHas('Features', function ($data) use ($nama_fitur) {
            $data->where('nama_fitur', $nama_fitur);
        })->get();

        // mengambil packet soal pembahasan student yang sedang aktif
        $getPacketSoalPembahasanActive = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('feature_id', 2);
        })->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)
        ->where('student_id', $userId)->orderBy('created_at', 'desc')->first();

        return view('Features.payment-features.pembayaran-fitur', compact(
            'nama_fitur', 'features', 'paymentMethods', 'groupedPaymentMethods', 'dataFeaturesPrices', 'getPacketSoalPembahasanActive'
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
                'BC-co-sp' => CheckoutSoalPembahasanSubscriptionHandler::class,
                'BC-rnw-sp' => RenewCheckoutSoalPembahasanSubscriptionHandler::class
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
    public function renewCheckoutPacketFeatures(Request $request, String $id)
    {
        // Ambil transaksi dan pastikan milik user yang login
        $getDataTransactions = Transactions::with(['UserAccount', 'UserAccount.StudentProfiles'])->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$getDataTransactions) {
            return response()->json(['error' => 'Transaksi tidak ditemukan atau tidak sesuai user.'], 404);
        }

        // Ambil payment_method & feature_id dari transaksi pending
        $paymentMethod = $getDataTransactions->payment_method;
        $getFeatureId = $getDataTransactions->feature_id;

        // Map feature_id ke prefix order_id
        $prefixMap = [
            1 => 'BC-rnw-tanya-',
            2 => 'BC-rnw-sp-',
            // tambahkan fitur lain di sini
        ];

        if (!isset($prefixMap[$getFeatureId])) {
            return response()->json(['error' => 'Feature tidak dikenali.'], 400);
        }

        // Payment method mapping
        $paymentMap = [
            'bca'     => 'bca_va',
            'bni'     => 'bni_va',
            'bri'     => 'bri_va',
            'mandiri' => 'echannel',
            'qris'    => 'qris',
            'gopay'   => 'gopay',
            'dana'    => 'dana',
            'ovo'     => 'ovo',
        ];

        if (!array_key_exists($paymentMethod, $paymentMap)) {
            return response()->json(['error' => 'Metode pembayaran tidak dikenali.'], 400);
        }

        // Generate order_id baru
        $orderId = $prefixMap[$getFeatureId] . Str::uuid();

        // Update order_id di transaksi
        $getDataTransactions->update([
            'order_id' => $orderId,
        ]);

        // Parameter untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $getDataTransactions->price,
            ],
            'enabled_payments' => [$paymentMap[$paymentMethod]],
            'customer_details' => [
                'first_name' => $getDataTransactions->UserAccount->StudentProfiles->nama_lengkap ?? 'Customer',
                'email'      => $getDataTransactions->UserAccount->email ?? 'dummy@example.com',
            ],
        ];

        try {
            $today = now();
            // $today = Carbon::createFromFormat('Y-m-d', '2025-08-23')->startOfDay();

            if ($getDataTransactions->created_at->addDays(1) < $today) {
                return response()->json([
                    'status' => 'expired'
                ], 400);
            }

            // Generate Midtrans snap token
            $snap = MidtransService::createTransaction($params);

            // Update snap_token di transaksi
            $getDataTransactions->snap_token = $snap->token;
            $getDataTransactions->save();

            return response()->json([
                'snap_token' => $snap->token
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal membuat transaksi pembayaran.'
            ], 500);
        }
    }

    // FUNCTION CHECK TRANSACTION STATUS
    public function checkTransactionStatus(Request $request, String $id)
    {
        $getDataTransaction = Transactions::with(['UserAccount', 'UserAccount.StudentProfiles'])->where('id', $id)
            ->where('user_id', Auth::id())->where('transaction_status', 'Berhasil')->first();

        if (!$getDataTransaction) {
            return response()->json(['error' => 'Transaksi tidak ditemukan atau tidak sesuai user.'], 404);
        } else {
            return response()->json([
                'data' => $getDataTransaction,
                'status' => 'success',
            ]);
        }
    }


    // FUNCTION SOAL DAN PEMBAHASAN CHECKOUT
    public function checkoutSoalPembahasanSubcription(Request $request)
    {
        $user = Auth::user();
        $orderId = 'BC-co-sp-' . Str::uuid();
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
}