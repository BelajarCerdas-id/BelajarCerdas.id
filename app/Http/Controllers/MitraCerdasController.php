<?php

namespace App\Http\Controllers;

use App\Models\FeaturesRoles;
use App\Models\MentorFeatureStatus;
use App\Models\MentorProfiles;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MitraCerdasController extends Controller
{
    public function mentorView()
    {
        $dataMentor = MentorProfiles::with('UserAccount')->get();

        return view('Mentor.list-mentor', compact('dataMentor'));
    }

    public function listMentorUpdate(Request $request, $id)
    {
        $dataMentor = MentorProfiles::with('UserAccount')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:user_accounts,email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Harap masukkan email!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email telah terdaftar!',
            'password.required' => 'Harap masukkan password!',
            'password.min' => 'Minimal 8 karakter!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('formError_' . $id, 'update')
                ->with('formErrorId', $id)
                ->withInput(); // ini akan menyimpan semua input sesuai dengan nama field
        }


        // Update ke tabel user_accounts
        $user = $dataMentor->UserAccount; // mengambil relasi UserAccount dari model MentorProfiles
        $user->update([
            'email' => $request->email,
            'password' => bcrypt($request->password), // Gunakan bcrypt untuk keamanan
            'status_akun' => 'aktif',
        ]);

        // Update ke tabel mentor_profiles
        $dataMentor->update([
            'status_mentor' => 'Diterima',
        ]);

        return redirect()->back()->with('success-update-data-mentor', 'Akun Mentor berhasil diaktifkan!');
    }

    public function mentorAktifView()
    {
        $dataMentorAktif = MentorProfiles::with('UserAccount')->with('MentorFeatureStatus')->where('status_mentor', 'Diterima')->get();

        $dataFeaturesRoles = FeaturesRoles::with('Features')->where('feature_role', 'mentor')->get();

        $statusMentorFeature = [];

        foreach($dataMentorAktif as $mentor) {
            foreach($mentor->MentorFeatureStatus as $featureStatus) {
                $statusMentorFeature[$mentor->id][$featureStatus->feature_id] = $featureStatus->status_mentor;
            }
        }

        return view('Mentor.list-mentor-aktif', compact('dataMentorAktif', 'dataFeaturesRoles', 'statusMentorFeature'));
    }

    public function mentorFeatureActive(Request $request, $id)
    {
        $request->validate([
            'status_mentor' => 'required|in:aktif,tidak aktif',
        ]);

        $dataMentorFeatureStatus = MentorFeatureStatus::where('mentor_id', $id)->where('feature_id', $request->feature_id)->first();

        if($dataMentorFeatureStatus) {
            $dataMentorFeatureStatus->update([
                'status_mentor' => $request->status_mentor,
            ]);
        } else {
            MentorFeatureStatus::create([
                'mentor_id' => $id,
                'feature_id' => $request->feature_id,
                'status_mentor' => $request->status_mentor
            ]);
        }

        return response()->json(['message' => 'status mentor berhasil diperbarui']);
    }

}
