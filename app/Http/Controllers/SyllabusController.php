<?php

namespace App\Http\Controllers;

use App\Models\Bab;
use App\Models\BabFeatureStatus;
use App\Models\Fase;
use App\Models\FeaturesRoles;
use App\Models\Kurikulum;
use App\Models\Mapel;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SyllabusController extends Controller
{
    // KURIKULUM CONTROLLER
    public function curiculum()
    {
        $dataCuriculum = Kurikulum::all();

        return view('syllabus-services.list-kurikulum', compact('dataCuriculum'));
    }

    public function curiculumStore(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nama_kurikulum' => [
                'required',
                Rule::unique('kurikulums', 'nama_kurikulum')
            ],
        ], [
            'nama_kurikulum.required' => 'Harap masukkan nama kurikulum!',
            'nama_kurikulum.unique' => 'Nama kurikulum telah terdaftar!',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('formError', 'create')->withInput();
        }

        Kurikulum::create([
            'user_id' => $user->id,
            'nama_kurikulum' => $request->nama_kurikulum,
            'kode' => $request->nama_kurikulum,
        ]);

        return redirect()->back()->with('success-insert-data-kurikulum', 'Kurikulum Berhasil Ditambahkan');
    }

    public function curiculumUpdate(Request $request, String $id)
    {
        $dataCuriculum = Kurikulum::findOrFail($id);

        // metode validator ini bisa dipakai jika ada 2 form input didalam satu page dengan input name yang sama dan form 1 berada tanpa modal dan 1 nya lagi di dalam modal, agar validasi tidak terjadi tabrakan
        $validator = Validator::make($request->all(), [
            'nama_kurikulum' => [
                'required',
                Rule::unique('kurikulums', 'nama_kurikulum')
            ],
        ], [
            'nama_kurikulum.required' => 'Harap masukkan nama kurikulum!',
            'nama_kurikulum.unique' => 'Nama kurikulum telah terdaftar!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('formError_' . $id, 'update')
                ->with('formErrorId', $id)
                ->withInput(); // ini akan menyimpan semua input sesuai dengan nama field
        }

        $dataCuriculum->update([
            'nama_kurikulum' => $request->nama_kurikulum,
            'kode' => $request->nama_kurikulum,
        ]);

        return redirect()->back()->with('success-update-data-kurikulum', 'Nama Kurikulum Berhasil Diubah');
    }

    public function curiculumDelete($id)
    {
        $dataCuriculum = Kurikulum::findOrFail($id);

        // menghapus semua mapel yang terkait dengan kurikulum yang di delete
        $datamapel = Mapel::where('kurikulum_id', $dataCuriculum->id)->get();
        foreach ($datamapel as $mapel) {
            $mapel->delete();
        }

        // menghapus semua fase yang terkait dengan kurikulum yang di delete
        $dataFase = Fase::where('kurikulum_id', $dataCuriculum->id)->get();
        foreach ($dataFase as $fase) {
            $fase->delete();
        }

        // mengahapus semua bab yang terkait dengan kurikulum yang di delete
        $dataBab = Bab::where('kurikulum_id', $dataCuriculum->id)->get();
        foreach ($dataBab as $bab) {
            $bab->delete();
        }

        // menghapus data kurikulum
        $dataCuriculum->delete();

        return redirect()->back()->with('success-delete-data-kurikulum', 'Kurikulum Berhasil Dihapus');
    }

    public function fase($nama_kurikulum, $id)
    {
        $dataFase = Fase::where('kurikulum_id', $id)->get();

        return view('syllabus-services.list-fase', compact('id', 'nama_kurikulum', 'dataFase'));
    }

    public function faseStore(Request $request, $id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nama_fase' => [
                'required',
                Rule::unique('fases', 'nama_fase')->where('kurikulum_id', $id)
            ],
        ], [
            'nama_fase.required' => 'Harap masukkan Fase!',
            'nama_fase.unique' => 'Fase telah terdaftar!',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('formError', 'create')->withInput();
        }

        Fase::create([
            'user_id' => $user->id,
            'nama_fase' => $request->nama_fase,
            'kode' => $request->nama_fase,
            'kurikulum_id' => $id,
        ]);

        return redirect()->back()->with('success-insert-data-fase', 'Fase Berhasil Ditambahkan');
    }

    public function faseUpdate(Request $request, $id)
    {
        $dataFase = Fase::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_fase' => [
                'required',
                Rule::unique('fases', 'nama_fase')->where('kurikulum_id', $id)
        ],
        ], [
            'nama_fase.required' => 'Harap masukkan Fase!',
            'nama_fase.unique' => 'Fase telah terdaftar!',
        ]);

        if($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->with('formError_' .$id, 'update')
            ->with('formErrorId', $id)
            ->withInput();
        }

        $dataFase->update([
            'nama_fase' => $request->nama_fase,
            'kode' => $request->nama_fase,
        ]);

        return redirect()->back()->with('success-update-data-fase', 'Fase Berhasil Diubah');
    }

    public function faseDelete($id)
    {
        $dataFase = Fase::findOrFail($id);
        $dataMapel = Mapel::where('fase_id', $dataFase->id)->get();
        $dataBab = Bab::where('fase_id', $dataFase->id)->get();

        // menghapus semua mapel yang terkait dengan fase yang di delete
        foreach ($dataMapel as $mapel) {
            $mapel->delete();
        }

        // menghapus semua bab yang terkait dengan fase yang di delete
        foreach ($dataBab as $bab) {
            $bab->delete();
        }

        // menghapus fase
        $dataFase->delete();

        return redirect()->back()->with('success-delete-data-fase', 'Fase Berhasil Dihapus');
    }

    public function mapel($nama_kurikulum, $kurikulum_id, $nama_fase, $id)
    {
        $dataMapel = Mapel::where('fase_id', $id)->where('kurikulum_id', $kurikulum_id)->get();

        return view('syllabus-services.list-mapel', compact('nama_kurikulum', 'kurikulum_id', 'nama_fase', 'id', 'dataMapel'));
    }

    public function mapelStore(Request $request, $id, $kurikulum_id)
    {
        $user = Auth::user();

        $validatoor = Validator::make($request->all(), [
            'mata_pelajaran' => [
                'required',
                Rule::unique('mapels', 'mata_pelajaran')->where('fase_id', $id)->where('kurikulum_id', $kurikulum_id)
            ],
        ], [
            'mata_pelajaran.required' => 'Harap masukkan nama mapel!',
            'mata_pelajaran.unique' => 'Mapel telah terdaftar!',
        ]);

        if($validatoor->fails()) {
            return redirect()->back()->withErrors($validatoor)->with('formError', 'create')->withInput();
        }

        Mapel::create([
            'user_id' => $user->id,
            'mata_pelajaran' => $request->mata_pelajaran,
            'kode' => $request->mata_pelajaran,
            'fase_id' => $id,
            'kurikulum_id' => $kurikulum_id,
        ]);

        return redirect()->back()->with('success-insert-data-mapel', 'Mata pelajaran Berhasil Ditambahkan');
    }

    public function mapelUpdate(Request $request, $id)
    {
        $dataMapel = Mapel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'mata_pelajaran' => [
                'required', Rule::unique('mapels', 'mata_pelajaran')->where('fase_id', $id)
            ],
        ], [
            'mata_pelajaran.required' => 'Harap masukkan nama mata pelajaran!',
            'mata_pelajaran.unique' => 'Mata pelajaran telah terdaftar!',
        ]);

        if($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->with('formError_' .$id, 'update')
            ->with('formErrorId', $id)
            ->withInput();
        }

        $dataMapel->update([
            'mata_pelajaran' => $request->mata_pelajaran,
            'kode' => $request->mata_pelajaran,
        ]);

        return redirect()->back()->with('success-update-data-mapel', 'Mata pelajaran Berhasil Diubah');
    }

    public function mapelActivate(Request $request, $id)
    {
        $request->validate([
            'status_mata_pelajaran' => 'required|in:publish,unpublish',
        ]);

        $dataMapel = Mapel::findOrFail($id);
        $dataMapel->update([
            'status_mata_pelajaran' => $request->status_mata_pelajaran,
        ]);

        return response()->json(['message' => 'Status berhasil diperbarui']);
    }

    public function mapelDelete($id)
    {
        $dataMapel = Mapel::findOrFail($id);
        $dataBab = Bab::where('mapel_id', $dataMapel->id)->get();

        // menghapus semua bab yang terkait dengan mata_pelajaran yang di delete
        foreach ($dataBab as $bab) {
            $bab->delete();
        }

        // menghapus mata pelajaran
        $dataMapel->delete();

        return redirect()->back()->with('success-delete-data-mapel', 'Mata Pelajaran Berhasil Dihapus');
    }

    public function bab($nama_kurikulum, $kurikulum_id, $nama_fase, $fase_id, $mata_pelajaran, $id)
    {
        $dataBab = Bab::with('BabFeatureStatuses')->where('mapel_id', $id)->where('fase_id', $fase_id)->where('kurikulum_id', $kurikulum_id)->get();

        $dataFeaturesRoles = FeaturesRoles::with('Features')->where('feature_role', 'syllabus')->get();

        // Siapkan mapping id bab + feature_id => status
        $statusBabFeature = [];

        foreach ($dataBab as $bab) {
            foreach ($bab->BabFeatureStatuses as $featureStatus) {
                $statusBabFeature[$bab->id][$featureStatus->feature_id] = $featureStatus->status_bab;
            }
        }

        return view('syllabus-services.list-bab', compact('nama_kurikulum', 'kurikulum_id', 'nama_fase', 'fase_id', 'mata_pelajaran', 'id', 'dataBab', 'dataFeaturesRoles', 'statusBabFeature'));
    }

    public function babStore(Request $request, $id, $kurikulum_id, $fase_id)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'nama_bab' => [
                'required',
                Rule::unique('babs', 'nama_bab')->where('fase_id', $fase_id)->where('kurikulum_id', $kurikulum_id)->where('mapel_id', $id)
            ],
        ], [
            'nama_bab.required' => 'Harap masukkan bab!',
            'nama_bab.unique' => 'Bab telah terdaftar!',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('formError', 'create')->withInput();
        }

        Bab::create([
            'user_id' => $user->id,
            'nama_bab' => $request->nama_bab,
            'kode' => $request->nama_bab,
            'mapel_id' => $id,
            'fase_id' => $fase_id,
            'kurikulum_id' => $kurikulum_id,
        ]);

        return redirect()->back()->with('success-insert-data-bab', 'Bab Berhasil Ditambahkan');
    }

    public function babUpdate(Request $request, $kode_kurikulum, $nama_fase, $mata_pelajaran, $id)
    {
        $dataBab = Bab::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_bab' => [
                'required',
                Rule::unique('babs', 'nama_bab')->where('fase_id', $nama_fase)->where('kurikulum_id', $kode_kurikulum)->where('mapel_id', $mata_pelajaran)
            ],
        ], [
            'nama_bab.required' => 'Harap masukkan bab!',
            'nama_bab.unique' => 'Bab telah terdaftar!',
        ]);

        if($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->with('formError_' .$id, 'update')
            ->with('formErrorId', $id)
            ->withInput();
        }

        $dataBab->update([
            'nama_bab' => $request->nama_bab,
            'kode' => $request->nama_bab,
        ]);

        return redirect()->back()->with('success-update-data-bab', 'Bab Berhasil Diubah');
    }

    // public function babActivate(Request $request, $id)
    // {
    //     $request->validate([
    //         'status_bab' => 'required|in:publish,unpublish',
    //     ]);

    //     $dataBab = BabFeatureStatus::with('Bab')->with('Features')->findOrFail($id);

    //     $dataBab->update([
    //         'feature_id' => $dataBab->feature_id,
    //         'bab_id' => $dataBab->bab_id,
    //         'status_bab' => $request->status_bab
    //     ]);

    //     return response()->json(['message', 'Status berhasil diperbarui']);
    // }

    public function babActivate(Request $request, $id)
    {
        $request->validate([
            'status_bab' => 'required|in:publish,unpublish',
            'feature_id' => 'required|exists:features,id', // Pastikan feature_id valid
        ]);

        // Cari status yang sesuai untuk bab dan fitur
        $dataBabFeatureStatus = BabFeatureStatus::where('bab_id', $id)
            ->where('feature_id', $request->feature_id) // Pastikan feature_id diberikan
            ->first();

        if ($dataBabFeatureStatus) {
            // Jika data sudah ada, hanya update statusnya
            $dataBabFeatureStatus->update([
                'status_bab' => $request->status_bab
            ]);
        } else {
            // Jika data tidak ada, buat data baru
            BabFeatureStatus::create([
                'bab_id' => $id,
                'feature_id' => $request->feature_id,
                'status_bab' => $request->status_bab
            ]);
        }

        return response()->json(['message' => 'Status berhasil diperbarui']);
    }



    public function babDelete($id)
    {
        $dataBab = Bab::findOrFail($id);

        $dataBab->delete();

        return redirect()->back()->with('success-delete-data-bab', 'Bab Berhasil Dihapus');
    }
}