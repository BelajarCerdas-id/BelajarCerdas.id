<?php

namespace App\Http\Controllers;

use App\Models\test;
use Illuminate\Http\Request;

class testController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = test::all();
        $tests = test::withTrashed()->get();
        return view('test.index', compact('users', 'tests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = test::all();
        return view('test.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $users = test::all();
        $tests = test::withTrashed()->get();
        $request->validate([
            'nama' => 'required',
            'email' => 'required' 
        ]);
        $user = Test::create($request->all());
        $user->delete();
        // return redirect()->route('test')->with('success', 'User created successfully.');
        return view('test.index', compact('users', 'tests'));
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
        $user = test::findOrFail($id);
        $user->delete();
        return redirect()->route('/test')->with('success', 'User deleted successfully.');
    }

    // Mengembalikan pengguna yang dihapus
    public function restore($id)
    {
        $user = test::withTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('test')->with('success', 'User restored successfully.');
    }
}
