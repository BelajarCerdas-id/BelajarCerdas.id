<?php

namespace App\Http\Controllers;

use App\Models\Fase;
use App\Models\Keynote;
use Illuminate\Http\Request;

class CatatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = session('user');
        if(!$user) {
            return redirect('/login');
        };
        $fase = Fase::all();
        $catatan = Keynote::all();
        return view('catatan', compact('user', 'fase', 'catatan'));
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
        $user = session('user');
        $validatedData = $request->validate([
            'fase' => 'required',
            'kelas' => 'required',
            'mapel' => 'required',
            'bab' => 'required',
            'image_catatan' => '|image|mimes:jpg,png,jpeg|
            max:2000'
        ], [
            'fase.required' => 'Fase harus dipilih!',
            'kelas.required' => 'Kelas harus dipilih!',
            'mapel.required' => 'Mata Pelajran harus dipilih!',
            'bab.required' => 'Bab harus dipilih!',
            // 'image_catatan.required' => 'Harap untuk upload image!',
            'image_catatan.max' => 'Ukuran file melebihi ukuran maksimal yang ditentukan!'
        ]);

        if($request->hasFile('image_catatan')) {
            $filename = time() . $request->file('image_catatan')->getClientOriginalName();
            $request->file('image_catatan')->move(public_path('images_catatan'), $filename);
            $validatedData['image_catatan'] = $filename;
        } else {
            $validatedData['image_catatan'] = null; // Atau bisa hapus ini jika kolom tidak nullable
        }

        

        $users = Keynote::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'sekolah' => $request->sekolah,
            'fase' => $request->fase,
            'kelas' => $request->kelas,
            'no_hp' => $request->no_hp,
            'fase_catatan' => $request->fase_catatan,
            'kelas_catatan' => $request->kelas_catatan,
            'mapel' => $request->mapel,
            'bab' => $request->bab,
            'catatan' => $request->catatan,
            'image_catatan' => $validatedData['image_catatan'],
        ]);
        return view('catatan', compact('user'));
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
        //
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
