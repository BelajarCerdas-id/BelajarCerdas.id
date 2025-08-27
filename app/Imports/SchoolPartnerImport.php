<?php

namespace App\Imports;

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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Str;

class SchoolPartnerImport implements ToCollection, WithHeadingRow, WithStartRow, WithTitle
{
    /**
    * @param Collection $collection
    */
    protected $userId;
    protected $sheetTitle = '';

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

    public function collection(Collection $rows)
    {
        $errors = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 3;

            $validator = Validator::make($row->toArray(), [
                'nama_siswa' => 'required',
                'email_siswa' => 'required',
                'no_hp' => 'required',
                'fase' => 'required',
                'kelas' => 'required',
                'nama_sekolah' => 'required',
                'npsn' => 'required',
                'nama_kepsek' => 'required',
                'nik_kepsek' => 'required',
                'email_akun' => 'required',
                'password_akun' => 'required',
                'pembelian_fitur' => 'required',
                'varian_fitur' => 'required',
                'metode_pembayaran' => 'required',
            ], [
                "nama_siswa.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom nama siswa wajib diisi.",
                "email_siswa.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom email wajib diisi.",
                "no_hp.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom No.HP wajib diisi.",
                "fase.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom fase wajib diisi.",
                "kelas.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom kelas wajib diisi.",
                "nama_sekolah.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom nama sekolah wajib diisi.",
                "npsn.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom npsn wajib diisi.",
                "nama_kepsek.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom nama kepsek wajib diisi.",
                "nik_kepsek.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom nik kepsek wajib diisi.",
                "email_akun.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom email akun wajib diisi.",
                "password_akun.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom password akun wajib diisi.",
                "pembelian_fitur.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom pembelian fitur wajib diisi.",
                "varian_fitur.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom varian fitur wajib diisi.",
                "metode_pembayaran.required" => "Sheet {$this->sheetTitle} - Baris $rowNumber: Kolom metode pembayaran wajib diisi.",
            ]);

            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors()->all());
                continue;
            }

            // ambill tanggal hari ini
            $today = now()->format('Y-m-d');

            // ambil akun user
            $user = UserAccount::where('email', $row['email_akun'])->first();

            // ambil nama fitur yang sesuai dengan $row pada excel
            $feature = Features::where('nama_fitur', $row['pembelian_fitur'])->first();

            // ambil nama varian fitur yang dibeli
            $variantFeature = FeaturePrices::where('variant_name', $row['varian_fitur'])->first();

            // ambil fase
            $getFase = Fase::where('nama_fase', $row['fase'])->first();

            // ambil kelas
            $getKelas = Kelas::where('kelas', $row['kelas'])->first();

            // generate order id
            $orderId = 'BC-co-sch-' . Str::uuid();

            // jika user belum mempunyai akun, maka create
            $user = UserAccount::firstOrCreate(
                ['email' => $row['email_akun']], // unique column untuk deteksi
                [
                    'password' => bcrypt($row['password_akun']),
                    'no_hp' => $row['no_hp'],
                    'role' => 'Siswa',
                    'status_akun' => 'aktif',
                ]
            );

            // ambil subscription history
            $getSubscriptionHistory = FeatureSubscriptionHistory::where('student_id', $user->id)->whereHas('Transactions', function ($query) use ($feature) {
                $query->where('feature_id', $feature->id);
            })->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->first();

            // cek jika siswa masih memiliki fitur yang aktif, maka tampilkan error
            if ($getSubscriptionHistory) {
                $errors[] = "Sheet {$this->sheetTitle} - Baris $rowNumber: Siswa dengan email {$row['email_akun']} masih memiliki fitur {$row['pembelian_fitur']} yang aktif.";
                continue;
            }

            $createStudentProfiles = StudentProfiles::firstOrCreate([
                'personal_email' => $row['email_siswa'],
            ],
            [
                'user_id' => $user->id,
                'nama_lengkap' => $row['nama_siswa'],
                'sekolah' => $row['nama_sekolah'],
                'fase_id' => $getFase->id,
                'kelas_id' => $getKelas->id
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

            // aktifkan fitur yang telah dibeli
            $featureSubscriptionHistory = FeatureSubscriptionHistory::create([
                'student_id' => $user->id,
                'transaction_id' => $transaction->id,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            // insert school partner
            $schoolPartner = SchoolPartner::updateOrCreate([
                    'nama_sekolah' => $row['nama_sekolah'],
                    'npsn' => $row['npsn'],
                ],
                [
                    // 'nama_sekolah' => $row['nama_sekolah'],
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