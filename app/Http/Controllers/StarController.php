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
        $validated = $request->validate([
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

        // Redirect kembali ke halaman laporan yang sama sesuai dengan id yang dilihat (tanpa pagination)
        // return redirect()->route('laporan.edit', ['id' => $mentor->id]);

        // Mendapatkan parameter page dari form
        $page = $request->input('page', 1);

        // Redirect kembali ke halaman laporan yang sama (sesuai dengan id yang dilihat), dan tetap di halaman pagination yang terakhir dilihat user (pagination)
        return redirect()->route('laporan.edit', ['id' => $mentor->id, 'page' => $page]);

        // Mengambil data berdasarkan email dan batch
        // $users = Star::where('email', $email)->where('status', 'Diterima')->get();

        // // Menentukan start dan end berdasarkan batch yang dikirim
        // $start = ($batch - 1) * 3;
        // $batchUsers = $users->slice($start, 3); // Ambil 3 data untuk batch yang dipilih

        // // Mengupdate status pembayaran untuk batch ini
        // foreach ($batchUsers as $user) {
        //     if ($user->payment_status !== 'paid') {  // Jangan ubah jika sudah dibayar
        //     $user->payment_status = 'paid'; // Mengubah status pembayaran menjadi 'paid'
        //     $user->kode_payment = 'YA';    // Menandakan bahwa pembayaran sudah dilakukan
        //     $user->save();  // Menyimpan perubahan
        // }
        // }

        // return response()->json([
        //     'data' => $validated
        // ]);

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

    public function updatePaymentStatus(Request $request, $email, $batch)
    {
        dd($request->all());
        // Mengambil data berdasarkan email dan batch
        $users = Star::where('email', $email)->where('status', 'Diterima')->get();
        $backRoute = Crud::find($request->id);

        // Menentukan start dan end berdasarkan batch yang dikirim
        $start = ($batch - 1) * 3;
        $batchUsers = $users->slice($start, 3); // Ambil 3 data untuk batch yang dipilih

        // Mengupdate status pembayaran untuk batch ini
        foreach ($batchUsers as $user) {
            if ($user->payment_status !== 'paid') {  // Jangan ubah jika sudah dibayar
            $user->payment_status = 'paid'; // Mengubah status pembayaran menjadi 'paid'
            $user->kode_payment = 'YA';    // Menandakan bahwa pembayaran sudah dilakukan
            $user->save();  // Menyimpan perubahan
        }
        }

        $page = $request->input('page', 1);

        // Redirect kembali ke halaman dengan pesan sukses
        return redirect()->route('laporan.edit', ['id' => $backRoute->id, 'page' => $page])->with('status', 'Pembayaran berhasil!');
    }
}
