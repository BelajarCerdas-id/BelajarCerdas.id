<?php

namespace App\Http\Controllers;


use App\Models\userAccount;
use App\Models\Star;
use App\Models\Tanya;
use App\Models\testing;
use Illuminate\Http\Request;

class TanyaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // index for tanya siswa & murid
    public function index()
    {
        // mengambil tanggal hari ini
        $today = now();

        // ambil semua data di tanya (belum di soft delete)
        $getTanya = Tanya::paginate(1);

        // Retrieve questions related to the user's email
        //session data based on email
        $historyStudent = Tanya::where('email', session('user')->email)->orderBy('created_at', 'desc')->whereDate('created_at', $today)->get();
        // history terjawab
        $historyStudentAnswered = Tanya::onlyTrashed()->where('email', session('user')->email)->where('status', 'Diterima')->orderBy('created_at', 'desc')->whereDate('created_at', $today)->take(3)->get();
        // history ditolak
        $historyStudentReject = Tanya::onlyTrashed()->where('email', session('user')->email)->where('status', 'Ditolak')->orderBy('created_at', 'desc')->whereDate('created_at', $today)->take(3)->get();
        // kalau session data tidak mengambil data yang telah di soft delete, onlyTrashed nya hapus aja jadi langsung Tanya::where
        $siswaHistoryRestore = Tanya::onlyTrashed()->where('email', session('user')->email)->orderBy('created_at', 'desc')->paginate(2); // dataSiswa session tanya for page siswa (after soft delete)
        $teacherHistoryRestore = Tanya::onlyTrashed()->where('email_mentor', session('user')->email)->orderBy('created_at', 'desc')->get(); // getStore session tanya for page guru (after soft delete)

        $getData = userAccount::where('status', 'Mentor')->get();

        $dataAccept = Tanya::onlyTrashed()->whereIn('email_mentor', $getData->pluck('email'))->where('status', 'Diterima')->get()->groupBy('email_mentor');
        $validatedMentorAccepted = Star::whereIn('email', $getData->pluck('email'))->where('status', 'Diterima')->get()->groupBy('email');

        // limit tanya harian
        $getLimitedTanya = Tanya::where('email', session('user')->email)->whereDate('created_at', $today)->get();

        $countDataTanyaUser = Tanya::onlyTrashed()->where('email', session('user')->email)->where('status_soal', 'Belum Dibaca')->get();
        // Mengurutkan data berdasarkan urutan asli atau ID
        // $combinedHistory = $combinedHistory->sortBy('id'); // Ganti 'id' dengan atribut yang relevan jika diperlukan (id di hidden karena tidak butuh, tapi biarin aja)

        // Pass user data and filtered questions to the view
        return view('Tanya.tanya', compact('getTanya', 'historyStudent', 'historyStudentAnswered', 'historyStudentReject', 'teacherHistoryRestore', 'siswaHistoryRestore', 'dataAccept', 'validatedMentorAccepted', 'getLimitedTanya', 'countDataTanyaUser'));
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
    // store for tanya siswa & murid
    public function store(Request $request)
    {
        $user = session('user');
        if(!isset($user->email)) {
            return redirect('/login');
        }

        // Ensure the user's email exists
        if (!isset($user->email)) {
            return redirect('/login')->withErrors(['' => '']);
        }

        // Retrieve questions related to the user's email
        //session data based on email
        $dataSiswa = Tanya::where('email', $user->email)->get(); // session tanya for page siswa

        $validatedData = $request->validate([
            'nama_lengkap' => 'required',
            'kelas' => 'required',
            'mapel' => 'required',
            'bab' => 'required',
            'pertanyaan' => 'required',
            'image_tanya' => 'image|mimes:jpg,jpeg,png|max:2000'
        ], [
            'nama_lengkap.required' => 'Nama harus diisi',
            'kelas.required' => 'Harap Pilih Kelas!',
            'mapel.required' => 'Harap Pilih Mata Pelajaran!',
            'bab.required' => 'Harap Pilih Bab!',
            'pertanyaan.required' => 'Pertanyaan harus diisi!',
            'image_tanya.max' => 'File gambar melebihi jumlah ukuran maksimal'
        ]);

        if($request->hasFile('image_tanya')) {
            $filename = time() . $request->file('image_tanya')->getClientOriginalName();
            $request->file('image_tanya')->move(public_path('images_tanya'), $filename);
            $validatedData['image_tanya'] = $filename;
        } else {
            $validatedData['image_tanya'] = null; // Atau bisa hapus ini jika kolom tidak nullable
        }
        // Tanya::create($validatedData); insert data ver singkat, tapi harus di validated
        $users = Tanya::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'sekolah' => $request->sekolah,
            'fase' => $request->fase,
            'kelas' => $request->kelas,
            'mapel' => $request->mapel,
            'bab' => $request->bab,
            'pertanyaan' => $request->pertanyaan,
            'no_hp' => $request->no_hp,
            'image_tanya' => $validatedData['image_tanya'],
        ]);

        return redirect()->back()->with('success', 'Pertanyaan berhasil dikirim!');
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
        $getTanya = Tanya::find($id); // for answer
        $postReject = Tanya::withTrashed()->findOrFail($id); // for reject
        $getRestore = Tanya::withTrashed()->findOrFail($id);

        return view('Tanya.view', compact('getTanya', 'postReject', 'getRestore'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'jawaban' => 'required',
            'image_jawab' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ], [
            'jawaban.required' => 'Jawaban tidak boleh kosong',
            'image_jawab.max' => 'File gambar melebihi jumlah ukuran maksimal'
        ]);

        // Cari record yang akan diupdate
        $getTanya = Tanya::find($id);
        // Update data lain
        $getTanya->mentor = $request->mentor;
        $getTanya->id_mentor = $request->id_mentor;
        $getTanya->asal_mengajar = $request->asal_mengajar;
        $getTanya->email_mentor = $request->email_mentor;
        $getTanya->jawaban = $request->jawaban;
        $getTanya->status = $request->status;

        if ($request->hasFile('image_jawab')) {
            $filename = time() . $request->file('image_jawab')->getClientOriginalName();
            $request->file('image_jawab')->move(public_path('images_tanya'), $filename);
            $getTanya->image_jawab = $filename; // Simpan nama file baru ke database
        }
            $getTanya->save(); // save all update's in database
            $getTanya->delete(); // delete data after update data (supaya masuk ke softdelete)

        return redirect('/tanya')->with('success', 'Data berhasil diupdate!');
    }


    public function updateReject(Request $request, string $id)
    {
        $validatedReject = $request->validate([
            'alasan_ditolak' => 'required',
        ], [
            'alasan_ditolak.required' => 'Harap pilih alasan untuk menolak pertanyaan!'
        ]);

        $postReject = Tanya::find($id);
        $postReject->update([
            'mentor' => $request->mentor,
            'email_mentor' => $request->email_mentor,
            'asal_mengajar' => $request->asal_mengajar,
            'alasan_ditolak' => $request->alasan_ditolak,
            'status' => $request->status,
        ]);
        $postReject->delete(); // delete data after update data (supaya masuk ke softdelete)
        return redirect('/tanya');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function restore($id)
    {
        $user = Tanya::withTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('tanya')->with('flashdata', 'user restores succcessfully.');
    }

    public function viewRestore(string $id)
    {
        $getRestore = Tanya::withTrashed()->findOrFail($id);
        // findOrFail berfungsi untuk mencari semua record berdasarkan primary key (biasanya ID)
        // withTrashed mengambil semua record termasuk yang telah dihapus
        return view('Tanya.restore', compact('getRestore'));
    }

public function updateStatusSoal($email)
{
    $getTanya = Tanya::onlyTrashed()->where('email', $email)->get();

    foreach ($getTanya as $tanya) {
        $tanya->update(['status_soal' => 'Telah Dibaca']);
    }

    return response()->json([
        'success' => true,
        'message' => 'Status berhasil diperbarui!',
    ]);
}

}