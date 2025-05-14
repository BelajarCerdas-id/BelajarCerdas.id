<?php

namespace App\Http\Controllers;

use App\Models\Fase;
use App\Models\Features;
use App\Models\MentorFeatureStatus;
use App\Models\MentorProfiles;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profileUser()
    {
        // MENTOR
        $userMentor = UserAccount::where('role', 'Mentor')->first();

        $mentorProfiles = MentorProfiles::where('user_id', $userMentor->id)->first();
        // Ambil semua fitur mentor
        $mentorFeaturesStatus = MentorFeatureStatus::where('mentor_id', $mentorProfiles->id)->get();
        // Ambil semua feature_id yang ada
        $featureIds = $mentorFeaturesStatus->pluck('feature_id');
        // Ambil semua fitur berdasarkan feature_id
        $dataMentorAhli = Features::whereIn('id', $featureIds)->get();

        // SISWA
        $dataFase = Fase::all();

        return view('Profiles.profile-user', compact('dataMentorAhli', 'dataFase'));
    }

    // FUNCTION ATUR ULANG SANDI
    public function aturUlangSandi()
    {
        return view('Profiles.Settings.atur-ulang-sandi');
    }

    public function AturUlangSandiUpdate(Request $request)
    {
        $dataUser = UserAccount::all();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'new_password_confirmation' => 'required',
        ], [
            'current_password.required' => 'Kata Sandi lama tidak boleh kosong!',
            'new_password.required' => 'Kata Sandi baru tidak boleh kosong!',
            'new_password.min' => 'Minimal 8 karakter!',
            'new_password_confirmation.required' => 'Konfirmasi Kata Sandi baru tidak boleh kosong!',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // mengecek apakah kata sandi lama benar
        if(!Hash::check($request->current_password, $dataUser->password)) {
            return back()->withErrors($validator)->withErrors(['current_password' => 'Kata sandi lama salah'])->withInput();
        }

        // mengecek apakah konfirmasi kata sandi baru sesuai dengan kata sandi baru
        if($request->new_password != $request->new_password_confirmation) {
            return back()->withErrors($validator)->withErrors(['new_password_confirmation' => 'Konfirmasi kata sandi baru tidak sesuai'])->withInput();
        }

        /// mengecek apakah kata sandi baru sama dengan kata sandi lama
        if(Hash::check($request->new_password, $dataUser->password)) {
            return back()->withErrors($validator)->withErrors(['new_password' => 'Kata sandi baru tidak boleh sama dengan kata sandi lama'])->withInput();
        }

        $dataUser->update([
            'password' => bcrypt($request->new_password)
        ]);

        return redirect('/profile')->with('success-update-password', 'Password berhasil diubah!');
    }

    // FUNCTION UPDATE DATA MENTOR
    public function updatePersonalInformationMentor(Request $request, $id)
    {
        $dataMentorProfiles = UserAccount::with('MentorProfiles')->findOrFail($id);
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'no_hp' => 'required|unique:user_accounts,no_hp',
            'personal_email' => 'required|email|unique:mentor_profiles,personal_email',
            'sekolah_mengajar' => 'required',
        ], [
            'nama_lengkap.required' => 'Nama tidak boleh kosong!',
            'no_hp.required' => 'Nomor HP tidak boleh kosong!',
            'no_hp.unique' => 'Nomor HP telah terdaftar!',
            'personal_email.required' => 'Email tidak boleh kosong!',
            'personal_email.email' => 'Format email tidak valid!',
            'personal_email.unique' => 'Email telah terdaftar!',
            'sekolah_mengajar.required' => 'Sekolah mengajar tidak boleh kosong!',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('formErrorInformation_' . $id, 'update')->with('formErrorInformationId', $id)->withInput();
        }

        $user = $dataMentorProfiles->MentorProfiles;

        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'personal_email' => $request->personal_email,
            'sekolah_mengajar' => $request->sekolah_mengajar
        ]);

        $dataMentorProfiles->update([
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->back()->with('success-update-data-personal-mentor', 'Data berhasil diubah!');
    }

    public function updatePendidikanMentor(Request $request, $id)
    {
        $dataMentorProfiles = UserAccount::with('MentorProfiles')->findOrFail($id);
        $validator = Validator::make($request->all(), [
            'status_pendidikan' => 'required',
            'bidang' => 'required',
            'jurusan' => 'required',
        ], [
            'status_pendidikan.required' => 'Status pendidikan tidak boleh kosong!',
            'bidang.required' => 'Bidang tidak boleh kosong!',
            'jurusan.required' => 'Jurusan tidak boleh kosong!',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('formErrorPendidikan_' . $id, 'update')->with('formErrorPendidikanId', $id)->withInput();
        }

        $userMentorProfiles = $dataMentorProfiles->MentorProfiles;

        $userMentorProfiles->update([
            'status_pendidikan' => $request->status_pendidikan,
            'bidang' => $request->bidang,
            'jurusan' => $request->jurusan,
            'tahun_lulus' => $request->tahun_lulus,
        ]);

        return redirect()->back()->with('success-update-data-pendidikan-mentor', 'Data berhasil diubah!');
    }

    // FUNCTION UPDATE DATA STUDENT
    public function updatePersonalInformationStudent(Request $request, $id)
    {
        $dataStudentProfiles = UserAccount::with('StudentProfiles')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'no_hp' => 'required|unique:user_accounts,no_hp',
            'email' => 'required|email|unique:user_accounts,email',
        ], [
            'nama_lengkap.required' => 'Nama tidak boleh kosong!',
            'no_hp.required' => 'Nomor HP tidak boleh kosong!',
            'no_hp.unique' => 'Nomor HP telah terdaftar!',
            'email.required' => 'Email tidak boleh kosong!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email telah terdaftar!',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('formErrorInformation_' . $id, 'update')->with('formErrorInformationId', $id)->withInput();
        }

        $user = $dataStudentProfiles->StudentProfiles;

        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
        ]);

        $dataStudentProfiles->update([
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->back()->with('success-update-data-personal-student', 'Data berhasil diubah!');
    }

    public function updatePendidikanStudent(Request $request, $id)
    {
        $dataStudentProfiles = UserAccount::with('StudentProfiles')->findOrFail($id);
        $validator = Validator::make($request->all(), [
            'sekolah' => 'required',
            'fase_id' => 'required',
        ], [
            'sekolah.required' => 'Sekolah tidak boleh kosong!',
            'fase_id.required' => 'Fase tidak boleh kosong!',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('formErrorPendidikan_' . $id, 'update')->with('formErrorPendidikanId', $id)->withInput();
        }

        $userStudentProfiles = $dataStudentProfiles->StudentProfiles;

        $userStudentProfiles->update([
            'sekolah' => $request->sekolah,
            'fase_id' => $request->fase_id,
            'kelas_id' => $request->kelas_id,
        ]);

        return redirect()->back()->with('success-update-data-pendidikan-student', 'Data berhasil diubah!');
    }
}