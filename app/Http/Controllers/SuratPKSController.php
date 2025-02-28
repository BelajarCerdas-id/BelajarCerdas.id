<?php

namespace App\Http\Controllers;

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

        return view('surat-pks', compact('user'));
    }

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
            '1' => 'satu',
            '2' => 'dua',
            '3' => 'tiga',
            '4' => 'empat',
            '5' => 'lima',
            '6' => 'enam',
            '7' => 'tujuh',
            '8' => 'delapan',
            '9' => 'sembilan',
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
            '21' => 'Dua Satu',
            '22' => 'Dua Dua',
            '23' => 'Dua Tiga',
            '24' => 'Dua Empat',
            '25' => 'Dua Lima',
            '26' => 'Dua Enam',
            '27' => 'Dua Tujuh',
            '28' => 'Dua Delapan',
            '29' => 'Dua Sembilan',
            '30' => 'Tiga Puluh',
            '31' => 'Tiga Satu',
        ];

        return $words[$number] ?? 'Tidak diketahui';
    }

    public function generateSuratPKSEnglishZone()
    {
        // Ambil data pengguna dari session
        $user = session('user');
        $nama = strtoupper($user->nama_lengkap ?? 'Nama Pengguna');

        $getYear = now()->format('Y');
        $getMonth = now()->locale('id')->translatedFormat('F');
        $getDate = $this->numberToWords(now()->format('d'));
        $getDay = now()->locale('id')->translatedFormat('l');

        // Path ke file PDF template
        $pdfPath = public_path('surat_pks_sekolah/1740332994pks-belajar cerdas.docx.pdf');

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
                $pdf->SetFont('Arial', 'B', 18);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(63, 91);
                $pdf->Cell(170, 10, $nama, 0, 1, 'C');

                $pdf->SetFont('Arial', '', 9);
                $pdf->SetXY(54.5, 135);
                $pdf->Cell(180, 5,  $getYear, 0, 1, 'C');

                $pdf->SetFont('Arial', '', 10);
                $pdf->SetXY(31.5, 135);
                $pdf->Cell(180, 5,  $getMonth, 0, 1, 'C');

                $pdf->SetFont('Arial', '', 10);
                $pdf->SetXY(34, 135);
                $pdf->Cell(110, 5,  $getDate, 0, 1, 'C');

                $pdf->SetFont('Arial', '', 10);
                $pdf->SetXY(0, 135);
                $pdf->Cell(110, 5,  $getDay, 0, 1, 'C');
            }
        }

        // Output PDF ke browser
        return Response::make($pdf->Output('S', 'sertifikat.pdf'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="sertifikat.pdf"',
        ]);
    }
}