<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use App\Models\Fase;
use App\Models\Star;
use App\Models\Tanya;
use App\Models\testing;
use App\Models\tanyaAccess;
use App\Models\userAccount;
use Illuminate\Support\Str;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Events\QuestionAsked;
use App\Models\FeaturePrices;
use App\Models\TanyaUserCoin;
use App\Events\QuestionAnswered;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\PaymentHandlers\CheckoutCoinHandler;
use App\Services\PaymentHandlers\RenewCheckoutCoinHandler;

class TanyaController extends Controller
{
    // index for tanya siswa & murid
    public function index()
    {
        // mengambil tanggal hari ini
        $today = now();

        // ambil semua data di tanya (belum di soft delete)
        $getTanya = Tanya::with('Student')->get();

        // Retrieve questions related to the user's email

        //session data based on email
        $historyStudent = Tanya::with('Kelas', 'Mapel', 'Bab')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->whereDate('created_at', $today)->get();

        // history terjawab
        $historyStudentAnswered = Tanya::with('Kelas', 'Bab')->onlyTrashed()->where('user_id', Auth::user()->id)->where('status_soal', 'Diterima')->orderBy('created_at', 'desc')->whereDate('created_at', $today)->get();

        // history ditolak
        $historyStudentReject = Tanya::with('Kelas', 'Bab')->onlyTrashed()->where('user_id', Auth::user()->id)->where('status_soal', 'Ditolak')->orderBy('created_at', 'desc')->whereDate('created_at', $today)->get();

        // kalau session data tidak mengambil data yang telah di soft delete, onlyTrashed nya hapus aja jadi langsung Tanya::where
        $siswaHistoryRestore = Tanya::onlyTrashed()->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(2); // dataSiswa session tanya for page siswa (after soft delete)
        $teacherHistoryRestore = Tanya::onlyTrashed()->where('mentor_id', Auth::user()->id)->orderBy('created_at', 'desc')->get(); // getStore session tanya for page guru (after soft delete)

        $getData = userAccount::where('status', 'Mentor')->get();

        $dataAccept = Tanya::onlyTrashed()->whereIn('email_mentor', $getData->pluck('email'))->where('status', 'Diterima')->get()->groupBy('email_mentor');
        $validatedMentorAccepted = Star::whereIn('email', $getData->pluck('email'))->where('status', 'Diterima')->get()->groupBy('email');

        // limit tanya harian
        $getLimitedTanya = Tanya::whereIn('id', $historyStudentAnswered->pluck('id'))->where('email', Auth::user()->id)->whereDate('created_at', $today)->get();

        $countDataTanyaAnsweredUser = Tanya::onlyTrashed()->where('user_id', Auth::user()->id)->where('status_soal_student', 'Belum Dibaca')->where('status_soal', 'Diterima')->whereDate('created_at', $today)->get();
        $countDataTanyaRejectedUser = Tanya::onlyTrashed()->where('user_id', Auth::user()->id)->where('status_soal_student', 'Belum Dibaca')->where('status_soal', 'Ditolak')->whereDate('created_at', $today)->get();

        // get data fase
        $getFase = Fase::all();
        // Mengurutkan data berdasarkan urutan asli atau ID
        // $combinedHistory = $combinedHistory->sortBy('id'); // Ganti 'id' dengan atribut yang relevan jika diperlukan (id di hidden karena tidak butuh, tapi biarin aja)

        // Pass user data and filtered questions to the view
        return view('Tanya.tanya', compact('getTanya', 'historyStudent', 'historyStudentAnswered', 'historyStudentReject', 'teacherHistoryRestore', 'siswaHistoryRestore', 'dataAccept', 'validatedMentorAccepted', 'getLimitedTanya', 'countDataTanyaAnsweredUser', 'countDataTanyaRejectedUser', 'getFase'));
    }

    // store for tanya siswa & murid
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'fase_id' => 'required',
            'kelas_id' => 'required',
            'mapel_id' => 'required',
            'bab_id' => 'required',
            'pertanyaan' => 'required',
            'image_tanya' => 'nullable|image|mimes:jpg,jpeg,png|max:2000'
        ], [
            'fase_id.required' => 'Harap pilih fase!',
            'kelas_id.required' => 'Harap Pilih kelas!',
            'mapel_id.required' => 'Harap Pilih mata pelajaran!',
            'bab_id.required' => 'Harap pilih bab!',
            'pertanyaan.required' => 'Pertanyaan harus diisi!',
            'image_tanya.max' => 'Ukuran gambar maksimal 2MB!',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('formError', 'create')->withInput();
        }


        $image = null;
        if ($request->hasFile('image_tanya')) {
            $filename = time() . '_' . $request->file('image_tanya')->getClientOriginalName();
            $request->file('image_tanya')->move(public_path('images_tanya'), $filename);
            $image = $filename;
        }
        if(TanyaUserCoin::where('user_id', $user->id)->first()->jumlah_koin < $request->harga_koin) {
            return redirect()->back()->with('not-enough-coin-tanya', 'Maaf, Koin anda tidak cukup untuk mata pelajaran ini, silahkan pilih mata pelajaran lain atau isi ulang koin anda.');
        }

        TanyaUserCoin::where('user_id', $user->id)->decrement('jumlah_koin', $request->harga_koin);


        $questionCreate = Tanya::create([
            'user_id' => $user->id,
            'fase_id' => $request->fase_id,
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'bab_id' => $request->bab_id,
            'harga_koin' => $request->harga_koin,
            'pertanyaan' => $request->pertanyaan,
            'image_tanya' => $image,
        ]);

        broadcast(new QuestionAsked($questionCreate))->toOthers();

        return redirect()->back()->with('success-insert-tanya', 'Pertanyaan berhasil dikirim!');
    }

    public function edit(string $id)
    {
        $getTanya = Tanya::with('Student.StudentProfiles', 'Kelas', 'Mapel', 'Bab')->find($id); // for answer

        $postReject = Tanya::withTrashed()->findOrFail($id); // for reject

        $getRestore = Tanya::withTrashed()->findOrFail($id);

        return view('Tanya.view', compact('getTanya', 'postReject', 'getRestore'));
    }

    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        // Validasi input
        $validatedData = $request->validate([
            'jawaban' => 'required',
            'image_jawab' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ], [
            'jawaban.required' => 'Jawaban tidak boleh kosong!',
            'image_jawab.max' => 'File gambar melebihi jumlah ukuran maksimal'
        ]);

        // Cari record yang akan diupdate
        $getTanya = Tanya::find($id);
        // Update data lain
        $getTanya->mentor_id = $user->id;
        $getTanya->jawaban = $request->jawaban;
        $getTanya->status_soal = 'Diterima';

        if ($request->hasFile('image_jawab')) {
            $filename = time() . $request->file('image_jawab')->getClientOriginalName();
            $request->file('image_jawab')->move(public_path('images_tanya'), $filename);
            $getTanya->image_jawab = $filename; // Simpan nama file baru ke database
        }
            $getTanya->save(); // save all update's in database
            $getTanya->delete(); // delete data after update data (supaya masuk ke softdelete)

        return redirect('/tanya')->with('success-answer-tanya', 'Jawaban telah terkirim!');
    }


    public function updateReject(Request $request, string $id)
    {
        $user = Auth::user();

        $validatedReject = $request->validate([
            'alasan_ditolak' => 'required',
        ], [
            'alasan_ditolak.required' => 'Harap pilih alasan untuk menolak pertanyaan!'
        ]);

        $postReject = Tanya::with('Student')->find($id);

        $tanyaKoinUsers = TanyaUserCoin::where('user_id', $postReject->Student->id)->get();

        foreach ($tanyaKoinUsers as $koinUser) {
            $koinUser->increment('jumlah_koin', $postReject->harga_koin);

            $koinUser->update([
                'harga_koin' => $koinUser->jumlah_koin
            ]);
        }


        $postReject->update([
            'mentor_id' => $user->id,
            'status_soal' => 'Ditolak',
            'alasan_ditolak' => $request->alasan_ditolak,
        ]);
        $postReject->delete(); // delete data after update data (supaya masuk ke softdelete)
        return redirect('/tanya')->with('success-reject-tanya', 'Pertanyaan berhasil ditolak!');
    }

    public function restore($id)
    {
        $user = Tanya::withTrashed()->with('Student.StudentProfiles', 'Mentor.MentorProfiles')->findOrFail($id);
        $user->restore();
        return redirect()->route('tanya')->with('flashdata', 'user restores succcessfully.');
    }

    public function viewRestore(string $id)
    {
        // findOrFail berfungsi untuk mencari semua record berdasarkan primary key (biasanya ID)
        // withTrashed mengambil semua record termasuk yang telah dihapus
        $getRestore = Tanya::with('Student.StudentProfiles', 'Mentor.MentorProfiles', 'Kelas', 'Mapel', 'Bab')->withTrashed()->findOrFail($id);
        return view('Tanya.restore', compact('getRestore'));
    }

    //updateStatusSoal di riwayat harian per satuan klik (siswa & murid)
    public function markQuestionAsReadById($id)
    {
        $getTanya = Tanya::onlyTrashed()->where('status_soal_student', 'Belum Dibaca')
        ->where(function ($query) {
            $query->where('status_soal', 'Diterima')->orWhere('status_soal', 'Ditolak');
        })->find($id);

        $getTanya->update([
            'status_soal_student' => 'Telah Dibaca'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui!',
        ]);
    }

    //updateStatusSoal semua di riwayat harian dalam satu klik menggunakan button (siswa & murid)
    public function markAllQuestionsAsReadById($id)
    {
        $getTanyaAnswered = Tanya::onlyTrashed()->where('user_id', $id)->where('status_soal_student', 'Belum Dibaca')->where('status_soal',  'Diterima')->get();

        foreach ($getTanyaAnswered as $tanya) {
            $tanya->update(['status_soal_student' => 'Telah Dibaca']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui!',
        ]);
    }

    public function markAllQuestionsRejectedAsReadById($id)
    {
        $getTanyaRejected = Tanya::onlyTrashed()->where('user_id', $id)->where('status_soal_student', 'Belum Dibaca')->where('status_soal', 'Ditolak')->get();

        foreach ($getTanyaRejected as $tanya) {
            $tanya->update(['status_soal_student' => 'Telah Dibaca']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui!',
        ]);
    }

    //updateStatusSoal riwayat harian pada saat melihat jawaban (siswa & murid)
    public function updateStatusSoalRestore($id)
    {
        $getRestore = Tanya::onlyTrashed()->find($id);

        $getRestore->update([
            'status_soal_student' => 'Telah Dibaca'
        ]);

        return view('Tanya.restore', compact('getRestore'));
    }

    public function tanyaAccess()
    {
        $dataTanyaAccess = tanyaAccess::all();

        $today = now();

        // 1. Jika status_access tidak aktif, dan tanggal mulai sudah lewat dari tanggal hari ini maka status_access diubah menjadi aktif
        tanyaAccess::whereIn('status_access', ['Tidak Aktif'])
            ->whereDate('tanggal_mulai', "<=", $today)
            ->whereDate('tanggal_akhir', '>=', $today)
            ->update(['status_access' => 'Aktif']);

        // 2. jika status_acccess aktif, dan tanggal_mulai sesuai atau lebih dari tanggal hari ini, dan tanggal_akhir sudah melewati tanggal hari ini maka status_access diubah menjadi tidak aktif
        tanyaAccess::whereIn('status_access', ['Aktif'])
            ->whereDate('tanggal_mulai', ">", $today)
            ->update(['status_access' => 'Tidak Aktif']);

        // 3. jika status_access aktif, dan tanggal_mulai sudah melewati tanggal hari ini maka status_access diubah menjadi tidak aktif
        tanyaAccess::whereIn('status_access', ['Aktif'])
            ->whereDate('tanggal_mulai', "<", $today)
            ->update(['status_access' => 'Tidak Aktif']);

        // 4. jika status_access aktif atau tidak aktif, dan tanggal_akhir sudah melewati tanggal hari ini maka status_access diubah menjadi tidak aktif
        tanyaAccess::whereIn('status_access', ['Tidak Aktif', 'Aktif'])
        ->whereDate('tanggal_akhir', "<", $today)
        ->update(['status_access' => 'Tidak Aktif']);

        return view('Tanya.tanya-access', compact('dataTanyaAccess'));
    }

    public function tanyaAccessStore(Request $request)
    {
        $user = Auth::user();

        $getDataTanyaAccess = tanyaAccess::all();

        $request->validate([
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
        ], [
            'nama_lengkap.required' => 'Harap masukkan nama lengkap!',
            'status.required' => 'Harap isi status!',
            'tanggal_mulai.required' => 'Harap pilih tanggal mulai!',
            'tanggal_akhir.required' => 'Harap pilih tanggal akhir!',
        ]);

        if($getDataTanyaAccess->isEmpty()) {
            tanyaAccess::create([
                'user_id' => $user->id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
            ]);
            return redirect()->back()->with('success-insert-data', 'Data berhasil disimpan!');
        } else {
            return redirect()->back()->with('failed-insert-data', 'Data libur telah terdaftar, silahkan edit data untuk melakukan perubahan!.');
        }
    }

    public function updateTanyaAccess(Request $request, String $id)
    {
        $request->validate([
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
        ], [
            'tanggal_mulai.required' => 'Harap pilih tanggal mulai!',
            'tanggal_akhir.required' => 'Harap pilih tanggal akhir!',
        ]);

        $updateDataLibur = tanyaAccess::find($id);

        $updateDataLibur->update([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir
        ]);

        return redirect()->back()->with('success-update-data', 'Data libur berhasil diubah!');
    }

    public function coinPackage()
    {
        $paymentMethods = [
            [
                'id' => '1',
                'tipe_payment' => 'ATM / Bank Transfer',
                'logo-payment' => 'image/logo-payment-method/bca.png',
                'name' => 'BCA'
            ],
            [
                'id' => '2',
                'tipe_payment' => 'ATM / Bank Transfer',
                'logo-payment' => 'image/logo-payment-method/BNI.png',
                'name' => 'BNI'
            ],
            [
                'id' => '3',
                'tipe_payment' => 'ATM / Bank Transfer',
                'logo-payment' => 'image/logo-payment-method/bri.png',
                'name' => 'BRI'
            ],
            [
                'id' => '4',
                'tipe_payment' => 'ATM / Bank Transfer',
                'logo-payment' => 'image/logo-payment-method/mandiri.png',
                'name' => 'MANDIRI'
            ],
            [
                'id' => '5',
                'tipe_payment' => 'E-Wallet',
                'logo-payment' => 'image/logo-payment-method/dana.png',
                'name' => 'DANA'
            ],
            [
                'id' => '6',
                'tipe_payment' => 'E-Wallet',
                'logo-payment' => 'image/logo-payment-method/gopay.png',
                'name' => 'GOPAY'
            ],
            [
                'id' => '7',
                'tipe_payment' => 'E-Wallet',
                'logo-payment' => 'image/logo-payment-method/ovo.png',
                'name' => 'OVO'
            ],
            [
                'id' => '8',
                'tipe_payment' => 'E-Wallet',
                'logo-payment' => 'image/logo-payment-method/qris.png',
                'name' => 'QRIS'
            ],
        ];
        $groupedPaymentMethods = collect($paymentMethods)->groupBy('tipe_payment');

        $dataCoinOptions = FeaturePrices::where('type', 'coin')->get();

        return view('Tanya.coin-package', compact('dataCoinOptions', 'paymentMethods', 'groupedPaymentMethods'));
    }

    public function checkout(Request $request)
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

    public function checkoutPending(Request $request, String $id)
    {
        $getDataTransactions = Transactions::find($id);
        $orderId = 'BC-rnw-tanya-' . Str::uuid(); // Generate new order_id
        $paymentMethod = strtolower($request->payment_method_id);

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

        // if ($transaction) {
        //     $transaction->transaction_status = $localStatus;
        //     $transaction->payment_method = $paymentMethod;
        //     $transaction->save();

        //     if ($localStatus == 'Berhasil') {
        //         $userId = $transaction->user_id;
        //         $jumlahKoin = $transaction->jumlah_koin;

        //         $tanyaUserCoin = TanyaUserCoin::where('user_id', $userId)->first();

        //         if ($tanyaUserCoin) {
        //             $tanyaUserCoin->update([
        //                 'jumlah_koin' => $tanyaUserCoin->jumlah_koin + $jumlahKoin
        //             ]);
        //             $transaction->update([
        //                 'transaction_status' => 'Berhasil'
        //             ]);
        //         } else {
        //             TanyaUserCoin::create([
        //                 'user_id' => $userId,
        //                 'jumlah_koin' => $jumlahKoin
        //             ]);
        //         }
        //     }
        // }

        return response()->json(['message' => 'Callback received.']);
    }
}