<?php

namespace App\Http\Controllers;

use App\Models\Tanya;
use App\Models\Keynote;
use Illuminate\Http\Request;

class FilterController extends Controller
{
public function filterHistoryStudent(Request $request)
{
    $user = session('user');

    if (!isset($user->email)) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $query = Tanya::onlyTrashed()->where('email', $user->email);

    // Apply the status filter if provided
    if ($request->filled('status') && $request->status !== 'semua') {
        $query->where('status', $request->status);
    }

    // Paginate the filtered results
    $data = $query->orderBy('created_at', 'desc')->paginate(5);

    return response()->json([
        'data' => $data->items(),
        'links' => (string) $data->links(), // Convert pagination links to string
        'restoreUrl' => route('getRestore.edit', ':id')
    ]);
}

public function filterHistoryTeacher(Request $request)
{
    $user = session('user');

    if (!isset($user->email)) {
        return redirect('/login');
    }

    $query = Tanya::onlyTrashed()->where('email_mentor', $user->email);

    if($request->filled('status') && $request->status !== 'semua') {
        $query->where('status', $request->status);
    }

    $data = $query->orderBy('created_at', 'desc')->paginate(10);

    return response()->json([
        'data' => $data->items(),
        'links' => (string) $data->links(),
        'restoreUrl' => route('getRestore.edit', ':id')
    ]);
}


public function filterTanyaTeacher(Request $request)
{
    $user = session('user');

    if(!isset($user->email)) {
        return redirect('/login');
    }

    $query = Tanya::query();

    if($request->filled('status') && $request->status !== 'semua') {
        $query->where('status', $request->status);
    }

    $data = $query->orderBy('created_at', 'desc')->paginate(10);

    return response()->json([
        'data' => $data->items(),
        'links' => (string) $data->links(),
        'restoreUrl' => route('tanya.edit', ':id')
    ]);
}

public function filterClassNote(Request $request)
{
    $user = session('user');

    if(!isset($user->email)) {
            return redirect('/login');
    }

    $query = Keynote::query();

    if ($request->filled('kelas_catatan') && $request->kelas_catatan !== 'semua') {
        $query->where('kelas_catatan', $request->kelas_catatan)->orWhere('mapel', $request->mapel);
    }

    $data = $query->orderBy('created_at', 'desc')->paginate(12);

    return response()->json([
        'data' => $data->items(),
        'links' => (string) $data->links(),
    ]);
}

public function filterMapelNote(Request $request)
{
    $user = session('user');

    if(!isset($user->email)) {
            return redirect('/login');
    }

    $query = Keynote::query();

    if($request->filled('mapel') && $request->mapel != 'semua') {
            $query->where('mapel', $request->mapel);
    };

    $data = $query->OrderBy('created_at', 'desc')->paginate(12);

    return response()->json([
        'data' => $data->items(),
        'links' => (string) $data->links(),    
    ]);
}

}