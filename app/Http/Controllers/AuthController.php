<?php

namespace App\Http\Controllers;

use App\Models\Crud;
use App\Models\Fase;
use App\Models\RegisterOtp;
use App\Models\userAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use App\Models\MentorProfiles;
use App\Models\studentProfiles;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus di isi',
            'password.required' => 'Password harus di isi',
        ]);

        // Use a raw SQL query to fetch the user
        $user = userAccount::where('email', $request->email)->first();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/beranda');
        }

        return redirect()->back()->with('alert-error-login', 'Akun tidak terdaftar, silahkan masukkan data yang valid!.');
    }

    public function indexRegister()
    {
        return view('Auth.register.daftar-user');
    }

    public function registerStudent()
    {
        $fase = Fase::all();

        return view('Auth.register.daftar-murid', compact('fase'));
    }

    public function registerMentor()
    {
        $fase = Fase::all();

        return view('Auth.register.daftar-mentor', compact('fase'));
    }

    public function registerStudentStore(Request $request)
    {
        $step = $request->step;
        $inputOTP = implode('', $request->otp);
        $inputEmail = $request->email;

        if ($step == 4) {
            $otpData = RegisterOtp::where('email', $inputEmail)->orderByDesc('created_at')->first();

            if(!$inputOTP) {
                return back()->withInput()->with([
                    'otp-error' => 'Kode OTP tidak boleh kosong.',
                    'step' => 4,
                ]);
            }

            if (!$otpData) {
                return back()->withInput()->with([
                    'otp-error' => 'kode OTP tidak ditemukan',
                    'step' => 4,
                ]);
            }

            if ($inputOTP != $otpData->otp) {
                return back()->withInput()->with([
                    'otp-error' => 'Kode OTP tidak valid.',
                    'step' => 4,
                ]);
            }

             // Cek apakah OTP sudah kadaluwarsa
            // if($inputOTP == $otpData->otp){
            //     if ($otpData->expires_at < now()) {
            //         return back()->withInput()->with([
            //             'otp-error' => 'Kode OTP telah kadaluarsa.',
            //             'step' => 4,
            //         ]);
            //     }
            // }

            $user = userAccount::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'no_hp' => $request->no_hp,
                'role' => 'Siswa',
                'status_akun' => 'aktif',
            ]);

            $studentProfiles = StudentProfiles::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->nama_lengkap,
                'personal_email' => $request->email,
                'sekolah' => $request->sekolah,
                'fase_id' => $request->fase_id,
                'kelas_id' => $request->kelas_id,
                'mentor_referral_code' => $request->mentor_referral_code,
            ]);

            $otpData->update([
                'status_otp' => 'Verified',
            ]);

            Auth::login($user);
            return redirect()->route('beranda')->with('success', 'Registrasi berhasil! Silakan login.');
        }
        // Jika bukan step 4, bisa redirect atau beri pesan error
        return redirect()->back()->with('error', 'Langkah registrasi tidak valid.');
    }

    public function registerMentorStore(Request $request)
    {
        $step = $request->step;
        $inputOTP = implode('', $request->otp);
        $inputEmail = $request->personal_email;

        $kodeReferral = rand('100000', '999999');

        if ($step == 4) {
            $otpData = RegisterOtp::where('email', $inputEmail)->orderByDesc('created_at')->first();

            if(!$inputOTP) {
                return back()->withInput()->with([
                    'otp-error' => 'Kode OTP tidak boleh kosong.',
                    'step' => 4,
                ]);
            }

            if (!$otpData) {
                return back()->withInput()->with([
                    'otp-error' => 'kode OTP tidak ditemukan',
                    'step' => 4,
                ]);
            }

            if ($inputOTP != $otpData->otp) {
                return back()->withInput()->with([
                    'otp-error' => 'Kode OTP tidak valid.',
                    'step' => 4,
                ]);
            }

             // Cek apakah OTP sudah kadaluwarsa
            // if($inputOTP == $otpData->otp){
            //     if ($otpData->expires_at < now()) {
            //         return back()->withInput()->with([
            //             'otp-error' => 'Kode OTP telah kadaluarsa.',
            //             'step' => 4,
            //         ]);
            //     }
            // }
            $user = userAccount::create([
                'no_hp' => $request->no_hp,
                'role' => 'Mentor',
            ]);

            $mentorProfiles = MentorProfiles::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->nama_lengkap,
                'status_pendidikan' => $request->status_pendidikan,
                'bidang' => $request->bidang,
                'jurusan' => $request->jurusan,
                'tahun_lulus' => $request->tahun_lulus,
                'sekolah_mengajar' => $request->sekolah_mengajar,
                'personal_email' => $request->personal_email,
                'kode_referral' => $kodeReferral,
            ]);

            $otpData->update([
                'status_otp' => 'Verified',
            ]);

            // return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
            return redirect()->back()->with('success-register-mentor', 'Terimakasih telah mendaftar sebagai Mitra Cerdas, Tim kami akan segera menghubungi untuk tahap selanjutnya.');
        }

         // Jika bukan step 4, bisa redirect atau beri pesan error
        return redirect()->back()->with('error', 'Langkah registrasi tidak valid.');
    }

    public function sendOtpMailStudent(Request $request)
    {
        $step = $request->input('step');
        $email = $request->input('email');

        // Validate the request
        $rules = [];
        $messages = [];

        if($step == 3) {
            $rules = [
                'email' => 'required|email|unique:student_profiles,personal_email|regex:/^[a-zA-z0-9._%+-]+@gmail\.com$/',
                'password' => 'required|min:8',
            ];
            $messages = [
                'email.required' => 'Email harus diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.regex' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password' => 'Password harus diisi.',
                'password.min' => 'Password minimal 8 karakter.',
            ];
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cek apakah user minta OTP terlalu cepat
        $lastOtp = RegisterOtp::where('email', $email)->latest()->first();

        // if ($lastOtp && $lastOtp->created_at->diffInSeconds(now()) < 60) {
        //     return response()->json(['message' => 'Tunggu 1 menit sebelum minta OTP lagi.'], 429);
        // }

        $otp = rand(100000, 999999);

        // Simpan OTP ke database
        RegisterOtp::create([
            'email' => $email,
            'otp' => $otp,
        ]);

        // Kirim email
        Mail::to($email)->queue(new SendOtpMail($otp));

        return response()->json(['message' => 'OTP berhasil dikirim']);
    }

    public function sendOtpMailMentor(Request $request)
    {
        $step = $request->input('step');
        $email = $request->input('personal_email');

        // Cek apakah user minta OTP terlalu cepat
        $lastOtp = RegisterOtp::where('email', $email)->latest()->first();

        // if ($lastOtp && $lastOtp->created_at->diffInSeconds(now()) < 60) {
        //     return response()->json(['message' => 'Tunggu 1 menit sebelum minta OTP lagi.'], 429);
        // }

        $otp = rand(100000, 999999);

        // Simpan OTP ke database
        RegisterOtp::create([
            'email' => $email,
            'otp' => $otp,
        ]);

        // Kirim email
        Mail::to($email)->queue(new SendOtpMail($otp));

        return response()->json(['message' => 'OTP berhasil dikirim']);
    }

    public function validateStepFormStudent(Request $request)
    {
        $step = $request->input('step');

        // Default kosong
        $rules = [];
        $messages = [];

        if ($step == 1) {
            $rules = [
                'nama_lengkap' => 'required|string|max:255',
                'no_hp' => 'required|numeric|unique:user_accounts,no_hp|regex:/^08\d{9,11}$/',
            ];
            $messages = [
                'nama_lengkap.required' => 'Nama tidak boleh kosong!',
                'no_hp.required' => 'No.HP tidak boleh kosong!',
                'no_hp.unique' => 'No.HP telah terdaftar!',
                'no_hp.regex' => 'No.HP tidak valid!',
                'no_hp.min' => 'Minimal 10 digit angka!',
            ];
        } elseif ($step == 2) {
            $rules = [
                'sekolah' => 'required',
                'fase_id' => 'required|string',
                'kelas_id' => 'required|string',
            ];
            $messages = [
                'sekolah.required' => 'Sekolah tidak boleh kosong!',
                'fase_id.required' => 'Fase tidak boleh kosong!',
                'kelas_id.required' => 'Kelas tidak boleh kosong!',
            ];
        } else {
            return response()->json(['status' => 'error', 'message' => 'Step tidak valid'], 422);
        }

        // Jalankan validasi
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
        return response()->json(['status' => 'success']);
    }

    public function validateStepFormMentor(Request $request)
    {
        $step = $request->input('step');

        // Default kosong
        $rules = [];
        $messages = [];

        if ($step == 1) {
            $rules = [
                'nama_lengkap' => 'required|string|max:255',
                'no_hp' => 'required|numeric|unique:user_accounts,no_hp|regex:/^08\d{9,11}$/',
                'personal_email' => 'required|email|unique:student_profiles,personal_email|regex:/^[a-zA-z0-9._%+-]+@gmail\.com$/',
            ];
            $messages = [
                'nama_lengkap.required' => 'Nama tidak boleh kosong!',
                'no_hp.required' => 'No.HP tidak boleh kosong!',
                'no_hp.unique' => 'No.HP telah terdaftar!',
                'no_hp.regex' => 'No.HP tidak valid!',
                'no_hp.min' => 'Minimal 10 digit angka!',
                'personal_email.required' => 'Email harus diisi!',
                'personal_email.email' => 'Format email tidak valid!',
                'personal_email.unique' => 'Email sudah terdaftar!',
                'personal_email.regex' => 'Format email tidak valid!',
            ];
        } elseif($step == 2) {
            $rules = [
                'status_pendidikan' => 'required',
                'bidang' => 'required',
                'jurusan' => 'required',
            ];
            $messages = [
                'status_pendidikan.required' => 'Harap pilih status pendidikan!',
                'bidang.required' => 'Harap pilih bidang!',
                'jurusan.required' => 'Harap isi jurusan!',
            ];
        } else {
            return response()->json(['status' => 'error', 'message' => 'Step tidak valid.']);
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
        return response()->json(['status' => 'success']);
    }


    public function logout(Request $request)
    {
        Auth::logout(); // Ini akan meng-logout user secara resmi dari sistem auth

        $request->session()->invalidate(); // menghapus semua sesi lama
        $request->session()->regenerateToken(); // mencegah CSRF reuse dari sesi lama

        return redirect('/');
    }
}