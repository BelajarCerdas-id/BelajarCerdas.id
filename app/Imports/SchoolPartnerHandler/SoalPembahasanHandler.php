<?php

namespace App\Imports\SchoolPartnerHandler;

use App\Events\SchoolPartnerSubscription;
use App\Models\Fase;
use App\Models\FeaturePrices;
use App\Models\Features;
use App\Models\FeatureSubscriptionHistory;
use App\Models\Kelas;
use App\Models\SchoolPartner;
use App\Models\StudentProfiles;
use App\Models\Transactions;
use App\Models\UserAccount;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SoalPembahasanHandler
{
    protected $userId;
    protected $sheetTitle;

    public function __construct($userId, $sheetTitle = '')
    {
        $this->userId = $userId;
        $this->sheetTitle = $sheetTitle;
    }

    public function title(): string
    {
        return $this->sheetTitle; // set sheet title untuk indetifikasi error pada sheet mana
    }

    public function headingRow(): int
    {
        return 2; // <-- kalo pake WithHeadingRow header row diambil dari kolom pertama, jadi kalo header row tidak di kolom pertama harus di return seperti ini
    }
    public function startRow(): int
    {
        return 3;
    }

    public function handle(Collection $rows)
    {
        $errors = [];
        $emailsInFile = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 3;

            $validator = Validator::make($row->toArray(), [
                'nama_siswa' => 'required',
                'email_siswa' => [
                    'required',
                    'email',
                    'regex:/^[a-zA-z0-9._%+-]+@gmail\.com$/',
                ],
                'no_hp' => [
                    'required',
                    'regex:/^08\d{9,11}$/',
                ],
                'fase' => 'required',
                'kelas' => 'required',
                'nama_sekolah' => 'required',
                'npsn' => 'required',
                'nama_kepsek' => 'required',
                'nik_kepsek' => 'required',
                'email_akun' => [
                    'required',
                    'email',
                    'regex:/^[a-zA-z0-9._%+-]+@belajarcerdas\.id$/',
                ],
                'password_akun' => 'required',
                'pembelian_fitur' => 'required',
                'durasi' => 'required',
                'metode_pembayaran' => 'required',
            ], [
                "nama_siswa.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom nama siswa wajib diisi.",
                'email_siswa.email' => "Sheet {$this->sheetTitle} - Baris $rowNumber: Format email_siswa harus @gmail.com.",
                'email_siswa.regex' => "Sheet {$this->sheetTitle} - Baris $rowNumber: Format email_siswa harus @gmail.com.",
                "no_hp.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom No.HP wajib diisi.",
                "no_hp.regex" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Format No.HP tidak valid.",
                "fase.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom fase wajib diisi.",
                "kelas.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom kelas wajib diisi.",
                "nama_sekolah.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom nama sekolah wajib diisi.",
                "npsn.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom npsn wajib diisi.",
                "nama_kepsek.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom nama kepsek wajib diisi.",
                "nik_kepsek.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom nik kepsek wajib diisi.",
                "email_akun.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom email akun wajib diisi.",
                'email_akun.email' => "Sheet {$this->sheetTitle} - Baris $rowNumber: Format email_akun harus @belajarcerdas.id.",
                'email_akun.regex' => "Sheet {$this->sheetTitle} - Baris $rowNumber: Format email_akun harus @belajarcerdas.id.",
                "password_akun.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom password akun wajib diisi.",
                "pembelian_fitur.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom pembelian fitur wajib diisi.",
                "durasi.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom durasi wajib diisi.",
                "metode_pembayaran.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom metode pembayaran wajib diisi.",
            ]);

            // ambill tanggal hari ini
            $today = now()->format('Y-m-d');

            $featureSubscriptionHistory = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) {
                $query->where('transaction_status', 'Berhasil');
            })->whereDate('end_date', '<', $today)->get();

            if ($featureSubscriptionHistory) {
                foreach ($featureSubscriptionHistory as $history) {
                    $history->update([
                        'subscription_status' => 'tidak_aktif'
                    ]);
                }
            }

            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors()->all());
                continue;
            }

            $email = strtolower($row['email_akun']);

            // tidak boleh ada duplikat email_akun pada saat import file
            if (in_array($email, $emailsInFile)) {
                $errors[] = "Sheet {$this->sheetTitle} - Terjadi duplikat email_akun pada user {$row['email_akun']}.";
                continue;
            }

            // Catat email yang sudah dipakai (supaya baris berikutnya tahu)
            $emailsInFile[] = $email;

            // ambil akun user
            $user = UserAccount::where('email', $row['email_akun'])->first();

            // ambil nama fitur yang sesuai dengan $row pada excel
            $feature = Features::where('nama_fitur', $row['pembelian_fitur'])->first();

            // ambil nama varian fitur yang dibeli
            if ($feature) {
                $variantFeature = FeaturePrices::where('variant_name', $row['durasi'])->where('feature_id', $feature->id)->first();
            }
            
            // ambil fase
            $getFase = Fase::where('nama_fase', $row['fase'])->first();

            // ambil kelas
            $getKelas = Kelas::where('kelas', $row['kelas'])->first();

            // validasi jika fitur tidak terdaftar
            if (!$feature) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: Fitur {$row['pembelian_fitur']} tidak terdaftar.";
                continue;
            }

            // validasi jika variant feature (durasi) tidak terdaftar
            if (!$variantFeature) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: Durasi {$row['durasi']} tidak terdaftar pada fitur {$row['pembelian_fitur']}.";
                continue;
            }

            // validasi jika fase tidak terdaftar
            if (!$getFase) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: Fase {$row['fase']} tidak terdaftar.";
                continue;
            }

            // validasi jika kelas tidak terdaftar
            if (!$getKelas) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: Kelas {$row['kelas']} tidak terdaftar.";
                continue;
            }

            if ($getKelas->fase_id !== $getFase->id) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: {$row['kelas']} tidak terdaftar pada {$row['fase']}.";
                continue;
            }

            // cek apakah email atau no_hp sudah ada di database
            // Cari user yang sudah ada berdasarkan email yang diinput dari file (kolom 'email_akun')
            $existingUserByEmail = UserAccount::where('email', $row['email_akun'])->first();

            // Cari user yang sudah ada berdasarkan nomor HP yang diinput dari file (kolom 'no_hp')
            $existingUserByPhone = UserAccount::where('no_hp', $row['no_hp'])->first();


            // ✅ Cek pertama: jika email sudah ada di database,
            // tapi nomor HP yang terdaftar pada email itu berbeda dengan yang ada di baris ini (satu email_akun hanya bisa digunakan oleh satu no_hp),
            if ($existingUserByEmail && $existingUserByEmail->no_hp !== $row['no_hp']) {
                // Tambahkan pesan error untuk baris ini agar user tahu penyebab duplikasi
                $errors[] = "Sheet {$this->sheetTitle} - Baris {$rowNumber}: Email {$row['email_akun']} sudah digunakan oleh akun lain dengan nomor HP berbeda.";
                continue; // lanjut ke baris berikutnya, tidak perlu proses user ini
            }


            // ✅ Cek kedua: jika nomor HP sudah ada di database,
            // tapi email yang terdaftar pada nomor HP itu berbeda dengan yang ada di baris ini (satu no_hp hanya bisa digunakan oleh satu email_akun),
            if ($existingUserByPhone && $existingUserByPhone->email !== $row['email_akun']) {
                // Tambahkan pesan error yang menjelaskan nomor HP sudah digunakan oleh email lain
                $errors[] = "Sheet {$this->sheetTitle} - Baris {$rowNumber}: Nomor HP {$row['no_hp']} sudah digunakan oleh akun lain dengan email berbeda ({$existingUserByPhone->email}).";
                continue; // skip baris ini
            }

            // generate order id
            $orderId = 'BC-co-sp-' . Str::uuid();

            // jika user belum mempunyai akun, maka create
            $user = UserAccount::firstOrCreate(
                [
                    'email' => $row['email_akun'],
                ], // unique column untuk deteksi
                [
                    'password' => bcrypt($row['password_akun']),
                    'no_hp' => $row['no_hp'],
                    'role' => 'Siswa',
                    'status_akun' => 'aktif',
                ]
            );

            // ambil subscription history
            $getSubscriptionHistory = FeatureSubscriptionHistory::where('student_id', $user->id)
                ->whereHas('Transactions', function ($query) use ($feature) {
                    $query->where('feature_id', $feature->id)
                        ->where('transaction_status', 'Berhasil')
                        ->where('transaction_source', 'school_partner');
                })->whereDate('end_date', '>=', $today)->where('subscription_status', 'aktif') // pastikan masih aktif
                ->first();


            // cek jika siswa masih memiliki fitur yang aktif, maka tampilkan error
            if ($getSubscriptionHistory) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: Siswa dengan email {$row['email_akun']} masih memiliki fitur {$row['pembelian_fitur']} yang aktif.";
                continue;
            }

            $createStudentProfiles = StudentProfiles::updateOrCreate([
                'personal_email' => $row['email_siswa'],
            ],
            [
                'user_id' => $user->id,
                'nama_lengkap' => $row['nama_siswa'],
                'sekolah' => $row['nama_sekolah'],
                'fase_id' => $getFase->id,
                'kelas_id' => $getKelas->id,
                'student_type' => 'school_partner',
            ]);

            // buat transaksi
            $transaction = Transactions::create([
                'user_id' => $user->id,
                'feature_id' => $feature->id,
                'feature_variant_id' => $variantFeature->id,
                'order_id' => $orderId,
                'payment_method' => $row['metode_pembayaran'],
                'transaction_status' => 'Berhasil',
                'price' => $variantFeature->price,
                'transaction_source' => 'school_partner',
            ]);

            $duration = $transaction->FeaturePrices->duration;
            $month = (int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT);

            $startDate = Carbon::now();
            $endDate = $startDate->copy()->addMonths($month);

            $featureSubscriptionHistory = FeatureSubscriptionHistory::create([
                'student_id' => $user->id,
                'transaction_id' => $transaction->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'fase_id' => $getFase->id
            ]);

            // insert school partner
            $schoolPartner = SchoolPartner::updateOrCreate([
                    'nama_sekolah' => $row['nama_sekolah'],
                    'npsn' => $row['npsn'],
                ],
                [
                    'nama_kepsek' => $row['nama_kepsek'],
                    'nik_kepsek' => $row['nik_kepsek'],
                ]);

            if ($featureSubscriptionHistory) {
                broadcast(new SchoolPartnerSubscription($schoolPartner))->toOthers();
            }
        }
        // Handle error
        if (!empty($errors)) {
            throw ValidationException::withMessages(['import' => $errors]);
        }
    }
}