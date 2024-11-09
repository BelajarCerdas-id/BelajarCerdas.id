<?php

namespace App\Http\Controllers;

use App\Models\Crud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus di isi',
            'password.required' => 'Password harus di isi',
        ]);

        // Use a raw SQL query to fetch the user
        $user = DB::table('cruds')->where('email', $request->email)->first();

        // Check if user exists and verify password
        if ($user && $user->password === $request->password) {
            // Store user data in session
            Session::put('user', $user);

            // Redirect to the intended page
            return redirect('/beranda');
        } else {
            return back()->with('alert', 'Akun tidak terdaftar, silahkan masukkan data yang valid');
        }
    }

    public function daftar(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'sekolah' => 'required',
            'fase' => 'required',
            'kelas' => 'required',
            'email' => 'required|unique:cruds,email',
            'password' => 'required',
            'no_hp' => 'required|unique:cruds,no_hp',
            'status' => 'required',
        ], [
            'nama_lengkap.required' => 'Nama harus diisi',
            'sekolah.required' => 'Sekolah harus diisi',
            'fase.required' => 'Fase harus diisi',
            'kelas.required' => 'Kelas harus diisi',
            'email.required' => 'Email harus diisi',
            'Email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'no_hp.required' => 'No.HP harus diisi',
            'no_hp.unique' => 'No.HP sudah terdaftar',  
            'status.required' => 'Status harus diisi'
        ]);
        $user = DB::table('cruds')->insert([
            'nama_lengkap' => $request->input('nama_lengkap'),
            'sekolah' => $request->input('sekolah'),
            'fase' => $request->input('fase'),
            'kelas' => $request->input('kelas'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'no_hp' => $request->input('no_hp'),
            'status' => $request->input('status')
        ]);
        if($user == TRUE) {
            return redirect()->route('login');
        }else {
            '';    
        }
        
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect('/login');
    }    
}