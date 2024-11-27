<?php

namespace App\Http\Controllers;


use App\Models\Crud;
use App\Models\Tanya;
use App\Models\testing;
use Illuminate\Http\Request;

class TanyaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ambil semua data di tanya (belum di soft delete)
        $user = session('user');
        $getTanya = Tanya::all();

        // Check if the user is logged in
        if (!$user) {
            return redirect('/login');
        }

        // Ensure the user's email exists
        if (!isset($user->email)) {
            return redirect('/login')->withErrors(['' => '']);
        }

        // Retrieve questions related to the user's email
        //session data based on email
        $historyStudent = Tanya::where('email', $user->email)->orderBy('created_at', 'desc')->take(3)->get();
        $historyStudentAnswered = Tanya::onlyTrashed()->where('email', $user->email)->where('status', 'Diterima')->orderBy('created_at', 'desc')->take(3)->get();
        $historyStudentReject = Tanya::onlyTrashed()->where('email', $user->email)->where('status', 'Ditolak')->orderBy('created_at', 'desc')->take(3)->get();
        // kalau session data tidak mengambil data yang telah di soft delete, onlyTrashed nya hapus aja jadi langsung Tanya::where
        $siswaHistoryRestore = Tanya::onlyTrashed()->where('email', $user->email)->orderBy('created_at', 'desc')->paginate(2); // dataSiswa session tanya for page siswa (after soft delete)
        $teacherHistoryRestore = Tanya::onlyTrashed()->where('email_mentor', $user->email)->orderBy('created_at', 'desc')->get(); // getStore session tanya for page guru (after soft delete)

        // Mengurutkan data berdasarkan urutan asli atau ID
        // $combinedHistory = $combinedHistory->sortBy('id'); // Ganti 'id' dengan atribut yang relevan jika diperlukan (id di hidden karena tidak butuh, tapi biarin aja)



        // Pass user data and filtered questions to the view
        return view('tanya', compact('user', 'getTanya', 'historyStudent', 'historyStudentAnswered', 'historyStudentReject', 'teacherHistoryRestore', 'siswaHistoryRestore'));
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
        if(!isset($user->email)) {
            return redirect('/login');
        }

        $getRestore = Tanya::withTrashed()->get();
        $historyStudent = Tanya::where('email', $user->email)->orderBy('created_at', 'desc')->take(3)->get();
        $siswaHistoryRestore = Tanya::onlyTrashed()->where('email', $user->email)->orderBy('created_at', 'desc')->paginate(2); // dataSiswa session tanya for page siswa (after soft delete)
        $historyStudentAnswered = Tanya::onlyTrashed()->where('email', $user->email)->where('status', 'Diterima')->orderBy('created_at', 'desc')->take(3)->get();
        $historyStudentReject = Tanya::onlyTrashed()->where('email', $user->email)->where('status', 'Ditolak')->orderBy('created_at', 'desc')->take(3)->get();

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
            'kelas.required' => 'Pilih Kelas',
            'mapel.required' => 'Pilih Mata Pelajaran',
            'bab.required' => 'Pilih Bab',
            'pertanyaan.required' => 'Pertanyaan harus diisi',
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

        return view('tanya', compact('user', 'dataSiswa', 'getRestore', 'historyStudent', 'siswaHistoryRestore', 'historyStudentAnswered', 'historyStudentReject'));
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
        $user = session('user');
        $getTanya = Tanya::find($id); // for answer
        $postReject = Tanya::withTrashed()->findOrFail($id); // for reject
        $getRestore = Tanya::withTrashed()->findOrFail($id);

        if(!$getTanya) { // if getTanya null, user will directed to the pages tanya
            return redirect('/tanya');
        }
        return view('view', compact('getTanya', 'postReject', 'getRestore', 'user'));
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
        $user = session('user');
        $getRestore = Tanya::withTrashed()->findOrFail($id); 
        // findOrFail berfungsi untuk mencari semua record berdasarkan primary key (biasanya ID)
        // withTrashed mengambil semua record termasuk yang telah dihapus
        return view('restore', compact('getRestore', 'user'));
    }
}