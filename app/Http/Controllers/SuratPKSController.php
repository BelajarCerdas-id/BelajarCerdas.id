<?php

namespace App\Http\Controllers;

use App\Models\dataSuratPks;
use App\Models\suratPks;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use setasign\Fpdi\Fpdi;

class SuratPKSController extends Controller
{
    public function index()
    {
        $user = session('user');

        if(!isset($user)) {
            return redirect('/login');
        }

        return view('templates.pks-template.surat-pks', compact('user'));
    }


    /**
     * Handle the upload of a Surat PKS file.
     *
     * This function validates the incoming request to ensure that all required fields
     * are present and that the uploaded file does not exceed the maximum size limit.
     * Once validated, the Surat PKS file is saved to the public directory and a new
     * record is created in the database.
     *
     * @param Request $request The HTTP request containing the uploaded file and form data.
     * @return \Illuminate\Http\RedirectResponse Redirects back with a success message upon successful upload.
     */

    public function uploadSuratPKS(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'status' => 'required',
            'surat_pks' => 'required|max:10000',
            'tipe_surat_pks' => 'required',
        ], [
            'nama_lengkap.required' => 'Harap isi nama lengkap!',
            'status.required' => 'Harap pilih status!',
            'surat_pks.required' => 'Harap upload surat PKS!',
            'surat_pks.max' => 'Ukuran file melebihi ukuran maksimal yang ditentukan!',
            'tipe_surat_pks.required' => 'Harap pilih tipe surat PKS!',
        ]);

        if ($request->hasFile('surat_pks')) {
            $suratPKS = time() . $request->file('surat_pks')->getClientOriginalName();
            $request->file('surat_pks')->move(public_path('surat_pks_sekolah'), $suratPKS);
        }

        suratPks::create([
            'nama_lengkap' => $request->nama_lengkap,
            'status' => $request->status,
            'surat_pks' => $suratPKS,
            'tipe_surat_pks' => $request->tipe_surat_pks,
        ]);

        return redirect()->back()->with('success', 'Surat PKS berhasil diupload!');
    }

    public function numberToWords($number)
    {
        $words = [
            '0' => 'nol',
            '01' => 'satu',
            '02' => 'Dua',
            '03' => 'tiga',
            '04' => 'empat',
            '05' => 'lima',
            '06' => 'enam',
            '07' => 'tujuh',
            '08' => 'delapan',
            '09' => 'sembilan',
            '10' => 'sepuluh',
            '11' => 'sebelas',
            '12' => 'dua belas',
            '13' => 'tiga belas',
            '14' => 'empat belas',
            '15' => 'lima belas',
            '16' => 'enam belas',
            '17' => 'tujuh belas',
            '18' => 'delapan belas',
            '19' => 'Sembilan belas',
            '20' => 'Dua Puluh',
            '21' => 'Dua Puluh Satu',
            '22' => 'Dua Puluh Dua',
            '23' => 'Dua Puluh Tiga',
            '24' => 'Dua Puluh Empat',
            '25' => 'Dua Puluh Lima',
            '26' => 'Dua Puluh Enam',
            '27' => 'Dua Puluh Tujuh',
            '28' => 'Dua Puluh Delapan',
            '29' => 'Dua Puluh Sembilan',
            '30' => 'Tiga Puluh',
            '31' => 'Tiga Puluh Satu',
        ];

        return $words[$number] ?? 'Tidak diketahui';
    }

    public function monthToRomawi($month)
    {
        $romawi = [
            'Januari' => 'I',
            'Februari' => 'II',
            'Maret' => 'III',
            'April' => 'IV',
            'Mei' => 'V',
            'Juni' => 'VI',
            'Juli' => 'VII',
            'Agustus' => 'VIII',
            'September' => 'IX',
            'Oktober' => 'X',
            'November' => 'XI',
            'Desember' => 'XII',
        ];

        return $romawi[$month] ?? 'Tidak diketahui';
    }

    public function generateSuratPKSEnglishZone($id, $sekolah)
    {
        // ambil tanggal hari ini
        $getYear = now()->format('Y');
        $getMonth = now()->locale('id')->translatedFormat('F');
        $getDayNumber = now()->format('d');
        $getDay = now()->locale('id')->translatedFormat('l');


        $getDateWords = $this->numberToWords(now()->format('d'));
        // ambil bulan hari ini to romawi
        $getMonthRomawi = $this->monthToRomawi(now()->locale('id')->translatedFormat('F'));

        // ambil data sekolah
        $getDataPKS = dataSuratPks::where('sekolah', $sekolah)->first();

        $getPKSId = dataSuratPks::find($id);

        $text = "Pada hari ini: $getDay, Tanggal $getDateWords Bulan $getMonth Tahun $getYear, yang bertandatangan di bawah ini:";

        // ambil data nomor surat keluar
        $textNomorSurat1 = sprintf("%04d", $getPKSId->id) . ".$getDayNumber/PKS-BC/$getMonthRomawi/$getYear";
        $textNomorSurat2 = "00$getPKSId->id.$getDayNumber/PKS-BC/$getMonthRomawi/$getYear";
        $textNomorSurat3 = "0$getPKSId->id.$getDayNumber/PKS-BC/$getMonthRomawi/$getYear";
        $textNomorSurat4 = "$getPKSId->id.$getDayNumber/PKS-BC/$getMonthRomawi/$getYear";
        // Path ke file PDF template
        $pdfPath = public_path('surat_pks_sekolah/1740933319pks-belajar cerdas.docx.pdf');

        // Buat objek FPDI
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($pdfPath); // Hitung jumlah halaman

        for ($i = 1; $i <= $pageCount; $i++) {
            $pdf->AddPage('p', 'A4'); // Tambah halaman baru
            $tplIdx = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tplIdx);
            $pdf->useTemplate($tplIdx, 0, 0, $size['width'], $size['height']);

            // Tambahkan teks hanya di halaman pertama
            if ($i === 1) {
                // add hari, tanggal, bulan, dan tahun sekarang
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetXY(24, 146);
                $pdf->MultiCell(145, 5, $text, 0, 'J');

                // add data sekolah
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetXY(100.5, 202);
                $pdf->MultiCell(85, 5,  $getDataPKS->nama_kepsek, 0, 'L');

                $pdf->SetFont('Arial', '', 10);
                $pdf->SetXY(58, 208);
                $pdf->Cell(107, 5,  $getDataPKS->nip_kepsek, 0, 1, 'C');

                $pdf->SetFont('Arial', '', 10);
                $pdf->SetXY(84, 214);
                $pdf->Cell(107, 5,  $getDataPKS->sekolah, 0, 1, 'C');

                $pdf->SetFont('Arial', '', 10);
                $pdf->SetXY(101, 221);
                $pdf->MultiCell(85, 5,  $getDataPKS->alamat_sekolah, 0, 'L');

                // add header
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(54, 101);
                $pdf->Cell(107, 5,  $getDataPKS->sekolah, 0, 1, 'C');

                // add nomor surat
                if($getPKSId->id < 10){
                    $pdf->SetFont('Arial', 'B', 11);
                    $pdf->SetXY(57, 114);
                    $pdf->Cell(107, 5,  $textNomorSurat1, 0, 1, 'C');
                }elseif($getPKSId->id < 100){
                    $pdf->SetFont('Arial', 'B', 11);
                    $pdf->SetXY(57, 114);
                    $pdf->Cell(107, 5,  $textNomorSurat2, 0, 1, 'C');
                }elseif($getPKSId->id < 1000){
                    $pdf->SetFont('Arial', 'B', 11);
                    $pdf->SetXY(57, 114);
                    $pdf->Cell(107, 5,  $textNomorSurat3, 0, 1, 'C');
                }else{
                    $pdf->SetFont('Arial', 'B', 11);
                    $pdf->SetXY(57, 114);
                    $pdf->Cell(107, 5,  $textNomorSurat4, 0, 1, 'C');
                }
            }

            if($i === 2){
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetXY(94, 214);
                $pdf->Cell(107, 5,  $getDataPKS->nama_kepsek, 0, 1, 'C');

                $pdf->SetFont('Arial', '', 9);
                $pdf->SetXY(94, 221);
                $pdf->Cell(107, 5,  'NIP.' .$getDataPKS->nip_kepsek, 0, 1, 'C');
            }
        }

        // Output PDF ke browser
        return Response::make($pdf->Output('S', 'sertifikat.pdf'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="sertifikat.pdf"',
        ]);
    }
}