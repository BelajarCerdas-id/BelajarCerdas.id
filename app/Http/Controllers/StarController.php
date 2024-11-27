<?php

namespace App\Http\Controllers;

use App\Models\Crud;
use App\Models\Star;
use App\Models\Tanya;
use Illuminate\Http\Request;

class StarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_mentor' => 'required',
            'email' => 'required',
            'sekolah' => 'required',
            'status' => 'required',
            'id_tanya' => 'required'
        ], [
            // validasi alert
        ]);

        $mentor = Crud::find($request->id);

        // Membuat data baru di database
        Star::create([
            'nama_mentor' => $request->nama_mentor,
            'email' => $request->email,
            'sekolah' => $request->sekolah,
            'status' => $request->status,
            'id_tanya' => $request->id_tanya
        ]);


        // Redirect ke route star.edit dengan ID yang baru saja dibuat
        return redirect()->route('laporan.edit', ['id' => $mentor->id]);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Mencari data berdasarkan ID
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
