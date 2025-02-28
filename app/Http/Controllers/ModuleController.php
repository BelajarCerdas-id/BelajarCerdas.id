<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function index()
    {
        $user = session('user');
        $modules = Module::orderBy('order', 'asc')->get();

        // Ambil module yang telah diselesaikan user
        $completedModules = UserProgress::where('user_id')->where('is_completed', true)->pluck('module_id')->toArray();

        // Tandai module yang terkunci
        foreach ($modules as $module) {
            $module->is_locked = !in_array($module->id, $completedModules) &&
                                ($module->order > 1 && !in_array($module->order - 1, $completedModules));
        }

        return view('modules.index', compact('modules'));
    }

    public function show($id)
    {
        $userId = session('user');
        $module = Module::findOrFail($id);

        // Cek apakah module sebelumnya telah diselesaikan
        if ($module->order > 1) {
            $previousModule = Module::where('order', $module->order - 1)->first();
            $previousProgress = UserProgress::where('user_id', $userId)
                                            ->where('module_id', $previousModule->id)
                                            ->where('is_completed', true)
                                            ->first();
            if (!$previousProgress) {
                return redirect()->route('modules.index')->with('error', 'Anda harus menyelesaikan module sebelumnya.');
            }
        }

        return view('modules.show', compact('module', 'userId'));
    }

    public function complete(Request $request, $id)
    {
        $user = $request->input('user_id');
        $module = Module::findOrFail($id);

        // Tandai module sebagai selesai
        UserProgress::updateOrCreate(
            ['user_id' => $user, 'module_id' => $module->id],
            ['is_completed' => true]
        );

        return redirect()->route('modules.index')->with('success', 'Module diselesaikan.');
    }

}