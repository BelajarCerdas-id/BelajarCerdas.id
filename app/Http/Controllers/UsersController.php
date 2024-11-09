<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['users'] = User::all();
        return view('users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required",
            "password" => "required",
            // "kelas" => "required",
            // "email" => "required",
            // "password" => "required",
            // "no_hp" => "required",
            // "status" => "required"
        ], [
            "name.required" => "Nama tidak boleh kosong",
            "email.required" => "sekolah tidak boleh kosong",
            "password.required" => "fase tidak boleh kosong",
            // "kelas.required" => "kelas tidak boleh kosong",
            // "email.required" => "email tidak boleh kosong",
            // "password.required" => "password tidak boleh kosong",
            // "no_hp.required" => "no.hp tidak boleh kosong",
            // "status.required" => "status tidak boleh kosong",
        ]);
        User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>$request->password,
            // "kelas"=>$request->kelas,
            // "email"=>$request->email,
            // "password"=>$request->password,
            // // "password"=>Hash::make($request->password),
            // "no_hp"=>$request->no_hp,
            // "status"=>$request->status
        ]);
        return redirect()->route('users');
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
        $user['cari'] = User::find($id);
        return view('users/edit', $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $password = $request->pasword;
        // jika variabel passwword kosong, yang disimpan hanya variabel ini. jika berisi maka yang disimpan yang else
        if(empty($password)) {
            $cari = User::find($id);
            $cari->update([
            "name"=>$request->name,
            "email"=>$request->email,
            // "kelas"=>$request->kelas,
            // "email"=>$request->email,
            // "password"=>$request->password,
            // // "password"=>Hash::make($request->password),
            // "no_hp"=>$request->no_hp,
            // "status"=>$request->status
        ]);
        }else {
            $cari = User::find($id);
            $cari->update([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>$request->password
            // "kelas"=>$request->kelas,
            // "email"=>$request->email,
            // "password"=>$request->password,
            // // "password"=>Hash::make($request->password),
            // "no_hp"=>$request->no_hp,
            // "status"=>$request->status
        ]);
        }
        return redirect()->route('users');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cari = User::find($id);
        $cari->delete();
        return redirect()->route('users');
    }
}
