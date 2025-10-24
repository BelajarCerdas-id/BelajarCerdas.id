<?php

namespace App\Imports\SchoolPartnerHandler;

use App\Events\SchoolPartnerSubscription;
use App\Models\EnglishZoneBatch;
use App\Models\EnglishZoneBatchSchedule;
use App\Models\EnglishZoneLevel;
use App\Models\EnglishZoneStudentBatch;
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

class EnglishZoneHandler
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
                'level' => 'required',
                'batch' => 'required',
                'batch_group' => 'required',
                'hari' => 'required',
                'jam' => 'required',
                'metode_pembayaran' => 'required',
            ], [
                "nama_siswa.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom nama siswa wajib diisi.",
                "email_siswa.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom email wajib diisi.",
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
                'level.required' => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom level wajib diisi.",
                'batch.required' => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom batch wajib diisi.",
                'batch_group.required' => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom batch_group wajib diisi.",
                'hari.required' => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom hari wajib diisi.",
                'jam.required' => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom jam wajib diisi.",
                "metode_pembayaran.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom metode pembayaran wajib diisi.",
            ]);

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

            // ambill tanggal hari ini
            $today = now()->format('Y-m-d');
            $now = Carbon::now();

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

            // ambil level
            if (is_array($row['level'])) {
                $levels = array_map('trim', $row['level']);
            } else {
                $levels = array_map('trim', explode(',', $row['level']));
            }

            // ambil id level
            $getLevel = EnglishZoneLevel::whereIn('level_name', $levels)->pluck('id')->toArray();

            // ambil batch
            $getBatch = EnglishZoneBatch::where('batch_name', $row['batch'])->first();

            // ambil batch group
            $getBatchGroup = EnglishZoneBatchSchedule::where('batch_schedule_group', $row['batch_group'])->where('batch_id', $getBatch->id)->first();

            // ambil hari
            $hari = $row['hari'];

            // ambil jam
            $jam = $row['jam'];

            // memisahkan jam mulai dan selesai
            [$start, $end] = array_map('trim', explode('-', $jam));

            // memisahkan hari
            $days = array_map('trim', explode('&', $hari));

            // Ambil daftar hari (day_of_week) yang sudah terdaftar untuk batch dan group tertentu,
            $existingDays = EnglishZoneBatchSchedule::whereIn('day_of_week', $days)->where('batch_schedule_group', $row['batch_group'])->where('batch_id', $getBatch->id)->pluck('day_of_week')->toArray();

            // Ambil daftar waktu mulai dan selesai (start_time → end_time) yang sudah ada 
            $existingHours = EnglishZoneBatchSchedule::whereIn('day_of_week', $days)->where('batch_schedule_group', $row['batch_group'])->where('batch_id', $getBatch->id)->pluck('end_time', 'start_time')->toArray();

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
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: {$row['fase']} tidak terdaftar.";
                continue;
            }

            // validasi jika kelas tidak terdaftar
            if (!$getKelas) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: {$row['kelas']} tidak terdaftar.";
                continue;
            }

            // validasi kelas jika tidak sesuai dengan fase yang di input
            if ($getKelas->fase_id !== $getFase->id) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: {$row['kelas']} tidak terdaftar pada {$row['fase']}.";
                continue;
            }

            // validasi jika level tidak terdaftar
            if (!$getLevel) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: Level {$row['level']} tidak terdaftar.";
                continue;
            }

            // validasi jika batch tidak terdaftar
            if (!$getBatch) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: {$row['batch']} tidak terdaftar.";
                continue;
            }

            // validasi jika batch group tidak terdaftar
            if (!$getBatchGroup) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: Batch Group {$row['batch_group']} tidak terdaftar pada {$row['batch']}.";
                continue;
            }

            // jika ada hari di $days yang belum terdaftar dalam $existingDays, maka tidak cocok
            $missingDays = array_diff($days, $existingDays);

            // jika ada waktu di $start dan $end yang belum terdaftar dalam $existingHours, maka tidak cocok
            $missingHours = array_diff([$start => $end], $existingHours);

            // Jika ditemukan hari yang tidak terdaftar di batch group, tambahkan pesan error dan lewati baris ini.
            if (!empty($missingDays)) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: Hari $hari tidak terdaftar pada batch group {$row['batch_group']}, {$row['batch']}.";
                continue;
            }

            // Jika ditemukan waktu yang tidak terdaftar di batch group, tambahkan pesan error dan lewati baris ini.
            if (!empty($missingHours)) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: Jam $jam tidak terdaftar pada hari $hari pada batch group {$row['batch_group']}, {$row['batch']}.";
                continue;
            }
            
            // cek apakah email atau no_hp sudah ada di database
            $existingUserByEmail = UserAccount::where('email', $row['email_akun'])->first();
            $existingUserByPhone = UserAccount::where('no_hp', $row['no_hp'])->first();

            // jika email yang digunakan sudah ada, cek apakah no_hp beda, jika beda tampilkan error
            if ($existingUserByEmail && $existingUserByEmail->no_hp !== $row['no_hp']) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris {$rowNumber}: Email {$row['email_akun']} sudah digunakan oleh akun lain dengan nomor HP berbeda.";
                continue;
            }

            // jika no_hp yang digunakan suda ada, cek apakah email_akun beda, jika beda tampilkan error
            if ($existingUserByPhone && $existingUserByPhone->email !== $row['email_akun']) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris {$rowNumber}: Nomor HP {$row['no_hp']} sudah digunakan oleh akun lain dengan email berbeda ({$existingUserByPhone->email}).";
                continue;
            }

            // memeriksa apakah $feature tidak kosong
            if ($feature) {
                // jika $variantFeature->variant_name === Langganan 3 Bulan || $variantFeature->id === 10
                if ($variantFeature->variant_name === 'Langganan 1 Bulan' || $variantFeature->id == 10) {
                    // jika $getLevel > 1 pada langganan 3 bulan, tampilkan error
                    if (count($getLevel) > 1) {
                        $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: pada durasi {$row['durasi']} hanya dapat dipilih satu level.";
                    }
                // jika $variantFeature->variant_name === Langganan 6 Bulan || $variantFeature->id === 11
                } else if ($variantFeature->variant_name === 'Langganan 6 Bulan' || $variantFeature->id == 11) {
                    // jika $getLevel > 2 pada langganan 6 bulan, tampilkan error
                    if (count($getLevel) > 2) {
                        $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: pada durasi {$row['durasi']} hanya dapat dipilih dua level.";
                    // jika $getLevel < 2 pada langganan 6 bulan, tampilkan error
                    } else if (count($getLevel) < 2) {
                        $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: pada durasi {$row['durasi']} level tidak boleh kurang dari dua.";
                    }
                // jika $variantFeature->variant_name === Langganan 9 Bulan || $variantFeature->id === 12
                } else if ($variantFeature->variant_name === 'Langganan 9 Bulan' || $variantFeature->id == 12) {
                    // jika $getLevel > 3 pada langganan 9 bulan, tampilkan error
                    if (count($getLevel) > 3) {
                        $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: pada durasi {$row['durasi']} hanya dapat dipilih tiga level.";
                    // jika $getLevel < 3 pada langganan 9 bulan, tampilkan error
                    } else if (count($getLevel) < 3) {
                        $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: pada durasi {$row['durasi']} level tidak boleh kurang dari tiga.";
                    }
                // jika $variantFeature->variant_name === Langganan 12 Bulan || $variantFeature->id === 13
                } else if ($variantFeature->variant_name === 'Langganan 12 Bulan' || $variantFeature->id == 13) {
                    // jika $getLevel > 4 pada langganan 12 bulan, tampilkan error
                    if (count($getLevel) > 4) {
                        $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: pada durasi {$row['durasi']} hanya dapat dipilih empat level.";
                    // jika $getLevel < 4 pada langganan 12 bulan, tampilkan error
                    } else if (count($getLevel) < 4) {
                        $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: pada durasi {$row['durasi']} level tidak boleh kurang dari empat.";
                    }
                }
            }

            // generate order id
            $orderId = 'BC-co-ez-' . Str::uuid();

            // ambil subscription history
            $getSubscriptionHistory = FeatureSubscriptionHistory::where('student_id', $user->id)
                ->whereHas('Transactions', function ($query) use ($feature) {
                    $query->where('feature_id', $feature->id)
                        ->where('transaction_status', 'Berhasil')
                        ->where('transaction_source', 'school_partner');
                })->whereDate('end_date', '>=', $today) // pastikan masih aktif
                ->first();

            // cek jika siswa masih memiliki fitur yang aktif, maka tampilkan error
            if ($getSubscriptionHistory) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: Siswa dengan email {$row['email_akun']} masih memiliki fitur {$row['pembelian_fitur']} yang aktif.";
                continue;
            }

            // ambil batch schedule ids
            $getBatchScheduleIds = EnglishZoneBatchSchedule::where('batch_id', $getBatch->id)->where('batch_schedule_group', $row['batch_group'])->where('start_time', $start)->where('end_time', $end)->whereIn('day_of_week', $days)->pluck('id')->toArray();

            // menghitung jumlah siswa yang terdaftar di suatu jadwal pada sekolah tersebut
            $studentCounts = EnglishZoneStudentBatch::whereIn('level_id', $getLevel)
            ->whereHas('EnglishZoneBatchSchedule', function ($q) use ($getBatchScheduleIds, $getBatch, $row) {
                $q->where('batch_id', $getBatch->id)->where('batch_schedule_group', $row['batch_group']);
                $q->whereIn('id', $getBatchScheduleIds);
            })
                ->whereHas('FeatureSubscriptionHistory.Transactions', function ($q) use ($variantFeature) {
                    $q->where('transaction_status', 'Berhasil')->where('transaction_source', 'school_partner')->where('feature_variant_id', $variantFeature->id);
                })
                ->whereHas('Student.StudentProfiles', function ($q) use ($row) {
                    $q->where('sekolah', $row['nama_sekolah']);
                })
                ->pluck('student_id')
                ->unique()
                ->count();

            // jika $studentCounts >= 10, maka tampilkan kapasitas penuh.
            if ($studentCounts >= 2) {
                $errors[] = "Jadwal {$row['batch']}, hari {$row['hari']}, jam {$row['jam']} pada sekolah $row[nama_sekolah] sudah penuh / melebihi jumlah kapasitas. Silahkan daftarkan siswa di jadwal lain.";
                continue;
            }

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

            // updateOrCreate student profiles
            $createStudentProfiles = StudentProfiles::updateOrCreate([
                'personal_email' => $row['email_siswa'],
            ],
            [
                'user_id' => $user->id,
                'nama_lengkap' => $row['nama_siswa'],
                'sekolah' => $row['nama_sekolah'],
                'fase_id' => $getFase->id,
                'kelas_id' => $getKelas->id
            ]);

            // transaction_callback
            $transactionCallback = [
                'level_id' => $getLevel,
                'batch_schedule_id' => $getBatchScheduleIds,
            ];

            // buat transaksi
            $transaction = Transactions::create([
                'user_id' => $user->id,
                'feature_id' => $feature->id,
                'feature_variant_id' => $variantFeature->id,
                'order_id' => $orderId,
                'payment_method' => $row['metode_pembayaran'],
                'transaction_status' => 'Berhasil',
                'transaction_callback' => $transactionCallback, 
                'price' => $variantFeature->price,
                'transaction_source' => 'school_partner',
            ]);

            // ambil duration dari feature price
            $duration = $transaction->FeaturePrices->duration;
            // ambil jumlah bulannya dari duration (1, 2, 3, 4, dst.)
            $month = (int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT);

            // ambil transaction_callback
            $transactionCallback = $transaction->transaction_callback;

            // ambil batch schedule id dari transaction_callback
            $batchScheduleIds = $transactionCallback['batch_schedule_id'];

            // ambil level id dari transaction_callback
            $levelIds = $transactionCallback['level_id'];

            // ambil jadwal pertama
            $firstSchedule = EnglishZoneBatchSchedule::with('EnglishZoneBatch')->find($batchScheduleIds[0]);

            // start date untuk fitur english zone
            $startDate = Carbon::create($now->year, (int) $firstSchedule->EnglishZoneBatch->start_day, (int) $firstSchedule->EnglishZoneBatch->start_month, $now->hour, $now->minute, $now->second);
            
            // jika start date sudah lewat dari $now, maka tambahkan 1 tahun
            if ($startDate->lt($now)) {
                $startDate->addYear();
            }

            // end date packet
            $endDate = $startDate->copy()->addMonths($month);

            // hitung jumlah level
            $levelCount = count($levelIds);

            // hitung jumlah bulan per level
            $monthPerlevel = $month / $levelCount;

            // insert feature subscription history
            $featureSubscriptionHistory = FeatureSubscriptionHistory::create([
                'student_id' => $user->id,
                'transaction_id' => $transaction->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            // insert student batch
            foreach ($batchScheduleIds as $batchScheduleId) {
                foreach ($levelIds as $index => $levelId) {
                    // Ambil tanggal mulai paket langganan (misal: 1 Januari 2025), lalu tambahkan bulan sesuai urutan level (index)
                    // ex: Level 1 (index = 0): 1 Jan + (3 * 0) = 1 Jan 2025, Level 2 (index = 1): 1 Jan + (3 * 1) = 1 Apr 2025
                    $levelStartDate = $startDate->copy()->addMonths($monthPerlevel * $index);

                    // Hitung tanggal selesai level ini, Tambahkan durasi belajar per level
                    // ex: Level 1: start 1 Jan  → end 1 Apr, Level 2: start 1 Apr  → end 1 Jul
                    $levelEndDate = $levelStartDate->copy()->addMonths($monthPerlevel);
                    
                    EnglishZoneStudentBatch::create([   
                        'student_id' => $user->id,
                        'subscription_history_id' => $featureSubscriptionHistory->id,
                        'level_id' => $levelId,
                        'level_start_date' => $levelStartDate,
                        'level_end_date' => $levelEndDate,
                        'batch_schedule_id' => $batchScheduleId,
                    ]);
                }
            }

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