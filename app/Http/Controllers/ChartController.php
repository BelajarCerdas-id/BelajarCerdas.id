<?php

namespace App\Http\Controllers;

use App\Models\Tanya;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function chartTanyaTahunan()
    {
        $allData = Tanya::withTrashed()->select('created_at')->get();

        // Proses pengelompokan di PHP
        $groupedData = $allData->groupBy(function ($item) {
            return $item->created_at->format('Y'); // Ambil tahun saja
        })->map(function ($group) {
            return $group->count(); // Hitung jumlah data di tahun tersebut
        });

        return response()->json($groupedData);
    }

    public function chartTanyaBulanan(Request $request)
    {
        $tahun = $request->input('tahun', date('Y')); // Default tahun ini jika tidak ada input

        $allData = Tanya::withTrashed()
            ->whereYear('created_at', $tahun) // Filter berdasarkan tahun
            ->select('created_at')
            ->get();

        $groupedData = $allData->groupBy(function ($item) {
            return $item->created_at->format('M'); // Kelompokkan berdasarkan bulan
        })->map(function ($group) {
            return $group->count();
        });

        // Ambil semua tahun yang tersedia
        $allYears = Tanya::withTrashed()
            ->select('created_at')->get()->map(function ($item) {
                return $item->created_at->year; // Ambil hanya tahun dari created_at
            });

        // Ambil tahun terbesar (jika ada data)
        $maxYear = $allYears->max();
        $minYear = $allYears->min(); // Default jika tidak ada data

        return response()->json([
        'data' => $groupedData,
        'tahun' => $tahun,
        'maxYear' => $maxYear,
        'minYear' => $minYear
    ]);
    }
    public function chartTanyaHarian(Request $request)
    {
        $bulan = $request->input('bulan', date('m')); // Pastikan bulan dalam format angka (01-12)
        $tahun = $request->input('tahun', date('Y')); // Ambil tahun dari request atau default tahun ini

        $minDate = Tanya::withTrashed()->orderBy('created_at', 'asc')->first();
        $maxDate = Tanya::withTrashed()->orderBy('created_at', 'desc')->first();

        // Ambil data yang hanya sesuai dengan bulan & tahun yang dipilih
        $allData = Tanya::withTrashed()
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->select('created_at')
            ->get();

        // Kelompokkan data berdasarkan tanggal saja
        $groupedData = $allData->groupBy(function ($item) {
            return $item->created_at->format('d-M'); // Ambil hanya tanggal (01-31)
        })->map(function ($group) {
            return $group->count(); // Hitung jumlah data per tanggal
        });

        // Ambil semua tahun yang tersedia
        $allMonths = Tanya::withTrashed()
            ->select('created_at')->get()->map(function ($item) {
                return $item->created_at->month; // Ambil hanya tahun dari created_at
            });

        return response()->json([
            'data' => $groupedData,
            'bulan' => date('F', mktime(0, 0, 0, $bulan, 1)), // Format nama bulan
            'tahun' => $tahun,
            'minMonth' => [
            'month' => $minDate ? $minDate->created_at->format('n') : date('n'),
            'year' => $minDate ? $minDate->created_at->format('Y') : date('Y')
            ],
            'maxMonth' => [
                'month' => $maxDate ? $maxDate->created_at->format('n') : date('n'),
                'year' => $maxDate ? $maxDate->created_at->format('Y') : date('Y')
            ]
        ]);
    }

}