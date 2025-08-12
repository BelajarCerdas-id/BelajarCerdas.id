<?php

namespace App\Services\PaymentHandlers;

use App\Models\FeatureSubscriptionHistory;
use App\Models\MentorPaymentDetail;
use App\Models\MentorPayments;
use App\Models\MentorProfiles;
use App\Models\StudentProfiles;
use App\Models\Transactions;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RenewCheckoutSoalPembahasanSubscriptionHandler
{
public static function handle(Transactions $transaction)
    {
        // ambil tanggal hari ini
        $date = Carbon::now()->format('Y-m-d');

        Log::info("Menjalankan handler CheckoutSoalPembahasanSubscriptionHandler", [
            'transaction_id' => $transaction->id,
            'student_id' => $transaction->user_id
        ]);

        if (!$transaction->FeaturePrices) {
            Log::warning('FeaturePrices NULL saat handle SoalPembahasanSubscription');
            return;
        }

        $duration = $transaction->FeaturePrices->duration;
        $month = (int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT);

        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addMonths($month);

        // mengambil packet soal pembahasan student yang sedang aktif
        $getPacketSoalPembahasanActive = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
            $query->where('feature_id', 2); // kalo uda ada fitur lain, bukan 2 lagi tapi != 1 (sesuai dengan id fitur tanya)
        })->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date)->where('student_id', $transaction->user_id)->orderBy('created_at', 'desc')->exists();

        // jika tidak ada packet soal pembahasan student yang sedang aktif, maka create
        if (!$getPacketSoalPembahasanActive) {
            FeatureSubscriptionHistory::create([
                'student_id' => $transaction->user_id,
                'transaction_id' => $transaction->id,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
        }

        Log::info("Berhasil membuat SoalPembahasanSubscription", ['user_id' => $transaction->user_id]);

        // ambil data student
        $getStudent = StudentProfiles::where('user_id', $transaction->user_id)->first();

        // mencari mentor yang memiliki kode referral yang sama dengan kode referral student
        $getMentor = MentorProfiles::where('kode_referral', $getStudent->mentor_referral_code)->first();

        // mencari semua id student yang memiliki kode referral yang sama dengan kode referral mentor
        $getStudentReferral = StudentProfiles::where('mentor_referral_code', $getMentor->kode_referral)->pluck('user_id');

        // hitung jumlah transaksi student
        $countTransactionsStudent = Transactions::whereIn('user_id', $getStudentReferral)->where('feature_id', '!=', 1)->count();

        // cek apakah history subscription dengan id transaksi pembelian ini sudah ada
        $alreadyExistsHistorySubscription = FeatureSubscriptionHistory::where('student_id', $transaction->user_id)->where('transaction_id', $transaction->id)->exists();

        // harga paket
        $price = $transaction->price;

        // diskon dan komisi 15% jika transaksi student
        if ($countTransactionsStudent > 3500) {
            $discountPercentage = 15;
            $commissionPercentage = 15;
        // diskon dan komisi 12.5% jika transaksi student
        } elseif ($countTransactionsStudent > 1000) {
            $discountPercentage = 12.5;
            $commissionPercentage = 12.5;
        // diskon dan komisi 10% jika transaksi student
        } elseif ($countTransactionsStudent > 0) {
            $discountPercentage = 10;
            $commissionPercentage = 10;
        // diskon dan komisi 0% jika tidak transaksi student
        } else {
            $discountPercentage = 0;
            $commissionPercentage = 0;
        }

        // menghitung jumlah diskon
        $discountAmount = $price * $discountPercentage / 100;

        // menghitung harga setelah diskon
        $priceAfterDiscount = $price - $discountAmount;

        // menghitung komisi
        $getReferralCommission = $priceAfterDiscount * $commissionPercentage / 100;

        // jika $alreadyExistsHistorySubscription ada maka buat history subscription
        if ($alreadyExistsHistorySubscription) {
            // jika $getMentor ada maka buat payment
            if ($getMentor) {
                $mentorPayments = MentorPayments::where('mentor_id', $getMentor->user_id)->first();

                if (!$mentorPayments || $mentorPayments->total_amount > 50000) {
                    $payMentor = MentorPayments::create([
                        'mentor_id' => $getMentor->user_id,
                        'total_amount' => $getReferralCommission,
                    ]);

                    $mentorPaymentDetail = MentorPaymentDetail::create([
                        'mentor_id' => $getMentor->user_id,
                        'payment_mentor_id' => $payMentor->id,
                        'source_payment_mentor' => 'Soal dan Pembahasan',
                        'amount' => $getReferralCommission
                    ]);
                } else {
                    $mentorPayments->update([
                        'total_amount' => $mentorPayments->total_amount + $getReferralCommission,
                    ]);

                    $mentorPaymentDetail = MentorPaymentDetail::create([
                        'mentor_id' => $getMentor->user_id,
                        'payment_mentor_id' => $mentorPayments->id,
                        'source_payment_mentor' => 'Soal dan Pembahasan',
                        'amount' => $getReferralCommission
                    ]);
                }
            }
        }
    }
}