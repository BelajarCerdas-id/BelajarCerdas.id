<?php

namespace App\Http\Controllers;

use App\Events\ManageOfficeAccounts;
use App\Models\OfficeProfiles;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfficeAccountController extends Controller
{
    // VIEW LIST OFFICE ACCOUNTS
    public function officeAccountsView()
    {
        return view('managements.office-accounts-management');
    }

    // OFFICE ACCOUNT STORE
    public function officeAccountsStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|max:255',
            'no_hp' => 'required|numeric|unique:user_accounts,no_hp|regex:/^08\d{9,11}$/',
            'email' => 'required|email|unique:user_accounts,email|regex:/^[a-zA-z0-9._%+-]+@belajarcerdas\.id$/',
            'password' => 'required|min:8',
            'role' => 'required',
        ], [
            'nama_lengkap.required' => 'Nama tidak boleh kosong.',
            'nama_lengkap.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'no_hp.required' => 'No.HP tidak boleh kosong.',
            'no_hp.unique' => 'No.HP telah terdaftar.',
            'no_hp.regex' => 'No.HP tidak valid.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.unique' => 'Email telah terdaftar.',
            'email.email' => 'Format email harus @belajarcerdas.id.',
            'email.regex' => 'Format email harus @belajarcerdas.id.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Role tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $createUsers = UserAccount::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'no_hp' => $request->no_hp,
            'role' => $request->role,
        ]);

        $createOfficeProfiles = OfficeProfiles::create([
            'user_id' => $createUsers->id,
            'nama_lengkap' => $request->nama_lengkap
        ]);

        broadcast(new ManageOfficeAccounts($createUsers))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    // PAGINATE LIST OFFICE ACCOUNTS (ADMINISTRATOR)
    public function paginateListOfficeAccounts(Request $request)
    {
        // ambil semua user id pada office profile
        $user = OfficeProfiles::pluck('user_id');

        // ambil data user dengan relasi office profile
        $officeAccounts = UserAccount::with('OfficeProfiles')
            ->whereIn('id', $user)
            ->orderBy('created_at', 'desc')
            ->get();

        // Apply the status filter if provided
        if ($request->filled('status_akun') && $request->status_akun !== 'semua') {
            $officeAccounts = $officeAccounts->where('role', $request->status_akun);
        }

        $officeAccounts = $officeAccounts->sortBy(fn($item) => $item->status_akun === 'aktif' ? 0 : 1)->values();

        // lalu paginate manual (kalo pake sortBy())
        $perPage = 20;
        $page = $request->input('page', 1);
        $pagedData = $officeAccounts->forPage($page, $perPage);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedData,
            $officeAccounts->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // ambil data user dengan relasi office profile dan sortBy role (A - Z)
        $officeAccountsRole = UserAccount::with('OfficeProfiles')->whereIn('id', $user)->get()->sortBy('role');

        // grouping data berdasarkan role
        $groupedRole = $officeAccountsRole->groupBy('role');

        return response()->json([
            'data' => $paginator->items(),
            'links' => (string) $paginator->links(),
            'role' => $groupedRole->values()
        ]);
    }


    public function officeAccountActivate(Request $request, $accountId)
    {
        $request->validate([
            'status_akun' => 'required|in:aktif,non-aktif'
        ]);

        $questionCreate = UserAccount::where('id', $accountId)->first();

        $questionCreate->update([
            'status_akun' => $request->status_akun
        ]);

        broadcast(new ManageOfficeAccounts($questionCreate))->toOthers();

        return response()->json([
            'status' => 'success',
            'data' => $questionCreate
        ]);
    }
}