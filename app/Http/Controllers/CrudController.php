<?php

namespace App\Http\Controllers;

use App\Models\Crud;
use Illuminate\Http\Request;

class CrudController extends Controller
{
    public function index()
    {
        $data['users'] = Crud::all();
        return view('crud.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('crud.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nama_lengkap" => "required",
            "sekolah" => "required",
            "fase" => "required",
            "kelas" => "required",
            "email" => "required|unique:cruds,email",
            "password" => "required",
            "no_hp" => "required|unique:cruds,no_hp",
            "status" => "required"
        ], [
            "nama_lengkap.required" => "Nama tidak boleh kosong",
            "sekolah.required" => "Sekolah tidak boleh kosong",
            "fase.required" => "Fase tidak boleh kosong",
            "kelas.required" => "Kelas tidak boleh kosong",
            "email.required" => "Email tidak boleh kosong",
            "email.unique" => "Email sudah terdaftar",
            "password.required" => "Password tidak boleh kosong",
            "no_hp.required" => "No.hp tidak boleh kosong",
            "no_hp.unique" => "No.HP sudah terdaftar",
            "status.required" => "Status tidak boleh kosong"
        ]);
        Crud::create([
            "nama_lengkap"=>$request->nama_lengkap,
            "sekolah"=>$request->sekolah,
            "fase"=>$request->fase,
            "kelas"=>$request->kelas,
            "email"=>$request->email,
            "password"=>$request->password,
            // "password"=>Hash::make($request->password),
            "no_hp"=>$request->no_hp,
            "status"=>$request->status
        ]);
        return redirect()->route('crud');
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
        $user['view'] = Crud::find($id);
        return view('crud/edit', $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // jika variabel passwword kosong, yang disimpan hanya variabel ini. jika berisi maka yang disimpan yang else
            $view = Crud::find($id);
            $view->update([
                "nama_lengkap" => $request->nama_lengkap,
                "sekolah" => $request->sekolah,
                "fase" => $request->fase,
                "kelas"=>$request->kelas,
                "email" => $request->email,
                "password"=>$request->password,
                "no_hp"=>$request->no_hp,
                "status"=>$request->status
            ]);
            $view = Crud::find($id);
            $view->update([
            "nama_lengkap"=>$request->nama_lengkap,
            "sekolah"=>$request->sekolah,
            "fase"=>$request->fase,
            "kelas"=>$request->kelas,
            "email"=>$request->email,
            "password"=>$request->password,
            "no_hp"=>$request->no_hp,
            "status"=>$request->status
            ]);
        return redirect()->route('crud');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $view = Crud::find($id);
        $view->delete();
        return redirect()->route('crud');
    }
}
