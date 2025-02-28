<?php

namespace App\Http\Controllers;

use FPDF;
use Illuminate\Http\Request;
use App\Models\englishZoneCertificate;

class CertificateController extends Controller
{

    public function certificateStore(Request $request)
    {
        $user = session('user');

        $request->validate([
            'certificate' => 'required|max:2000'
        ], [
            'image.required' => 'Harap untuk upload Certificate!',
            'image.max' => 'Ukuran file melebihi ukuran maksimal yang ditentukan!'
        ]);

        if ($request->hasFile('certificate')) {
            $certificate = time() . $request->file('certificate')->getClientOriginalName();
            $request->file('certificate')->move(public_path('englishZone_certificate'), $certificate);
        }

        englishZoneCertificate::create([
            'nama_lengkap' => $request->nama_lengkap,
            'status' => $request->status,
            'certificate' => $certificate
        ]);

        return redirect()->back();
    }
    public function generateCertificate()
    {
        // Ambil data pengguna (pastikan pengguna sudah login)
        $user = session('user');

        // Path ke gambar template
        $imagePath = public_path('englishZone_certificate/1739606839WhatsApp Image 2025-02-15 at 2.43.25 PM.jpeg');

        // Buat objek FPDF
        $pdf = new FPDF('L', 'mm', 'A4'); // Mode landscape, milimeter, A4
        $pdf->AddPage();

        // Tambahkan gambar template sebagai background
        if (file_exists($imagePath)) {
            $pdf->Image($imagePath, 0, 0, 297, 210); // Lebar 297mm, Tinggi 210mm (ukuran A4)
        }

        // Atur font dan warna teks
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetTextColor(0, 0, 0); // Warna hitam

        // Tampilkan Nama di sertifikat
        $pdf->SetXY(63, 91); // Posisi teks (X, Y)
        $pdf->Cell(170, 10, strtoupper('Muhammad Dede Supriyatna Rahmatullah'), 0, 1, 'C'); // Nama pengguna di tengah

        // Tambahkan Tanggal Sertifikat
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(50, 150);
        $pdf->Cell(200, 10, 'Diterbitkan: ' . now()->format('d F Y'), 0, 1, 'C');

        // Output PDF untuk diunduh
        return response($pdf->Output('S', 'sertifikat.pdf'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="sertifikat.pdf"');
    }
}