<?php

namespace App\Imports;

use App\Models\UserAccount;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class MuridImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithStartRow
// withHeadingRow digunakan untuk agar ToCollection bisa mengakses nama kolom dari file Excel. kalo tidak pakai maka harus menggunakan numerik ($row[1], $row[2], dst)
// SkipsEmptyRows digunakan untuk mengabaikan baris kosong agar tidak ter insert ke database
// WithStartRow digunakan untuk mengatur insert data dimulai dari baris berapa
{
    /**
    * @param Collection $rows
    */

    /**
     * Mulai import dari baris ke-3 (mengabaikan header)
     */
    public function startRow(): int
    {
        return 3;
    }

    /**
     * Proses import dan validasi
     */
    public function collection(Collection $rows)
    {
        $errors = [];
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 3; // Sesuaikan dengan startRow

            $validator = Validator::make($row->toArray(), [
                'nama_lengkap' => 'required',
                'sekolah' => 'required',
                'email' => 'required|unique:user_accounts,email',
                'password' => 'required',
                'no_hp' => 'required|numeric|unique:user_accounts,no_hp',
                'status' => 'required',
            ], [
                'nama_lengkap.required' => "Baris $rowNumber: Nama lengkap harus diisi!",
                'sekolah.required' => "Baris $rowNumber: Sekolah harus diisi!",
                'email.required' => "Baris $rowNumber: Email harus diisi!",
                'email.unique' => "Baris $rowNumber: Email telah terdaftar!",
                'password.required' => "Baris $rowNumber: Password harus diisi!",
                'no_hp.required' => "Baris $rowNumber: No HP harus diisi!",
                'no_hp.unique' => "Baris $rowNumber: No HP telah terdaftar!",
                'no_hp.numeric' => "Baris $rowNumber: No HP harus berupa angka!",
                'status.required' => "Baris $rowNumber: Status harus diisi!",
            ]);

            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors()->all());
                continue;
            }ß

            UserAccount::firstOrCreate([
                'nama_lengkap' => $row['nama_lengkap'],
                'sekolah' => $row['sekolah'],
                'fase' => $row['fase'] ?? null,
                'kelas' => $row['kelas'] ?? null,
                'email' => $row['email'] ?? null,
                'password' => $row['password'],
                // 'password' => bcrypt($row['password']), // Hash password
                'no_hp' => $row['no_hp'],
                'status' => $row['status'],
                'kode_jenjang_murid' => $row['kode_jenjang_murid'] ?? null,
            ]);
        }

        // Jika ada error, lempar ke controller
        if (!empty($errors)) {
            throw ValidationException::withMessages(['import' => $errors]);
        }
    }
}