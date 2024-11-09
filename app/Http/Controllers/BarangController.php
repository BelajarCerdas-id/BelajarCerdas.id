<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        return view('select', [
            'kategori' => Kategori::all()
        ]);
    }

    public function getBarang($id)
    {
        $barang = Barang::where('kode_kategori', $id)->get();
        return response()->json($barang);
    }
}
