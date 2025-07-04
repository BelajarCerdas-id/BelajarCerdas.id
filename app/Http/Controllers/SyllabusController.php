<?php

namespace App\Http\Controllers;

use App\Events\SyllabusCrud;
use App\Imports\SyllabusImport;
use App\Imports\SyllabusSheetImport;
use App\Models\Bab;
use App\Models\BabFeatureStatus;
use App\Models\Fase;
use App\Models\FeaturesRoles;
use App\Models\Kelas;
use App\Models\Kurikulum;
use App\Models\Mapel;
use App\Models\SubBab;
use App\Models\SubBabFeatureStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;

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

        $data = Kurikulum::create([
            'user_id' => $user->id,
            'nama_kurikulum' => $request->nama_kurikulum,
            'kode' => $request->nama_kurikulum,
        ]);

        broadcast(new SyllabusCrud('kurikulum', 'create', $data))->toOthers();

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
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422); // Gunakan 422 Unprocessable Entity untuk validasi
        }

        $dataCuriculum->update([
            'nama_kurikulum' => $request->nama_kurikulum,
            'kode' => $request->nama_kurikulum,
        ]);

        broadcast(new SyllabusCrud('kurikulum', 'update', $dataCuriculum))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Kurikulum berhasil diubah.',
            'data' => $dataCuriculum
        ]);
    }

    public function curiculumDelete($id)
    {
        $dataCuriculum = Kurikulum::findOrFail($id);

        // kalau datanya di delete, berarti harus kaya gini
        $deletedData = $dataCuriculum->toArray();
        // mendengarkan event listener ketika menghapus kurikulum
        broadcast(new SyllabusCrud('kurikulum', 'delete', $deletedData))->toOthers();

        // menghapus data kurikulum beserta relasi nya (fase, kelas, mapel, bab, subBab)
        $dataCuriculum->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kurikulum berhasil dihapus.',
            'data' => $dataCuriculum
        ]);
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

        $data = Fase::create([
            'user_id' => $user->id,
            'nama_fase' => $request->nama_fase,
            'kode' => $request->nama_fase,
            'kurikulum_id' => $id,
        ]);

        broadcast(new SyllabusCrud('fase', 'create', $data))->toOthers();

        return redirect()->back()->with('success-insert-data-fase', 'Fase Berhasil Ditambahkan');
    }

    public function faseUpdate(Request $request, $kurikulum_id, $id)
    {
        $dataFase = Fase::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_fase' => [
                'required',
                Rule::unique('fases', 'nama_fase')->where('kurikulum_id', $kurikulum_id)
        ],
        ], [
            'nama_fase.required' => 'Harap masukkan Fase!',
            'nama_fase.unique' => 'Fase telah terdaftar!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422); // Gunakan 422 Unprocessable Entity untuk validasi
        }

        $dataFase->update([
            'nama_fase' => $request->nama_fase,
            'kode' => $request->nama_fase,
        ]);

        broadcast(new SyllabusCrud('fase', 'update', $dataFase))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Fase berhasil diubah.',
            'data' => $dataFase
        ]);
    }

    public function faseDelete($id)
    {
        $dataFase = Fase::findOrFail($id);

        $dataMapel = Mapel::where('fase_id', $id)->get();

        foreach($dataMapel as $item) {
            $item->delete();
        }

        // kalau datanya di delete, berarti harus kaya gini
        $deletedData = $dataFase->toArray();
        // mendengarkan event listener ketika menghapus fase
        broadcast(new SyllabusCrud('kurikulum', 'delete', $deletedData))->toOthers();

        // menghapus fase beserta relasi nya (kelas, mapel, bab, subBab)
        $dataFase->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Fase berhasil dihapus.',
            'data' => $dataFase
        ]);
    }

    public function kelas($nama_kurikulum, $kurikulum_id, $fase_id)
    {
        $dataKelas = Kelas::where('fase_id', $fase_id)->where('kurikulum_id', $kurikulum_id)->get();

        return view('syllabus-services.list-kelas', compact('nama_kurikulum', 'kurikulum_id', 'fase_id', 'dataKelas'));
    }

    public function kelasStore(Request $request, $nama_kurikulum, $kurikulum_id, $fase_id)
    {
        $user = Auth::user();

        $validatoor = Validator::make($request->all(), [
            'kelas' => [
                'required',
                Rule::unique('kelas', 'kelas')->where('fase_id', $fase_id)->where('kurikulum_id', $kurikulum_id)
            ],
        ], [
            'kelas.required' => 'Harap masukkan kelas!',
            'kelas.unique' => 'Kelas telah terdaftar!',
        ]);

        if($validatoor->fails()) {
            return redirect()->back()->withErrors($validatoor)->with('formError', 'create')->withInput();
        }

        $data = Kelas::create([
            'user_id' => $user->id,
            'kelas' => $request->kelas,
            'kode' => $request->kelas,
            'fase_id' => $fase_id,
            'kurikulum_id' => $kurikulum_id,
        ]);

        broadcast(new SyllabusCrud('kelas', 'create', $data))->toOthers();

        return redirect()->back()->with('success-insert-data-kelas', 'Kelas Berhasil Ditambahkan');
    }

    public function kelasUpdate(Request $request, $kurikulum_id, $fase_id, $id)
    {
        $dataKelas = Kelas::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kelas' => [
                'required', Rule::unique('kelas', 'kelas')->where('fase_id', $dataKelas->fase_id)->where('kurikulum_id', $kurikulum_id)
            ],
        ], [
            'kelas.required' => 'Harap masukkan kelas!',
            'kelas.unique' => 'Kelas telah terdaftar!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422); // Gunakan 422 Unprocessable Entity untuk validasi
        }

        $dataKelas->update([
            'kelas' => $request->kelas,
            'kode' => $request->kelas,
        ]);

        broadcast(new SyllabusCrud('kelas', 'update', $dataKelas))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Kelas berhasil diubah.',
            'data' => $dataKelas
        ]);
    }

    public function kelasDelete($id)
    {
        $dataKelas = Kelas::findOrFail($id);

        // kalau datanya di delete, berarti harus kaya gini
        $deletedData = $dataKelas->toArray();

        // mendengarkan event listener ketika menghapus kelas
        broadcast(new SyllabusCrud('kelas', 'delete', $deletedData))->toOthers();

        // menghapus kelas beserta relasi nya (mapel, bab, subBab)
        $dataKelas->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kelas berhasil dihapus.',
            'data' => $dataKelas
        ]);
    }

    public function mapel($nama_kurikulum, $kurikulum_id, $fase_id, $kelas_id)
    {
        $dataMapel = Mapel::where('kelas_id', $kelas_id)->where('fase_id', $fase_id)->where('kurikulum_id', $kurikulum_id)->get();

        return view('syllabus-services.list-mapel', compact('nama_kurikulum', 'kurikulum_id', 'fase_id', 'kelas_id', 'dataMapel'));
    }

    public function mapelStore(Request $request, $nama_kurikulum, $kurikulum_id, $fase_id, $kelas_id)
    {
        $user = Auth::user();

        $validatoor = Validator::make($request->all(), [
            'mata_pelajaran' => [
                'required',
                Rule::unique('mapels', 'mata_pelajaran')->where('kelas_id', $kelas_id)->where('kurikulum_id', $kurikulum_id)
            ],
        ], [
            'mata_pelajaran.required' => 'Harap masukkan nama mapel!',
            'mata_pelajaran.unique' => 'Mapel telah terdaftar!',
        ]);

        if($validatoor->fails()) {
            return redirect()->back()->withErrors($validatoor)->with('formError', 'create')->withInput();
        }

        $data = Mapel::create([
            'user_id' => $user->id,
            'mata_pelajaran' => $request->mata_pelajaran,
            'kode' => $request->mata_pelajaran,
            'kelas_id' => $kelas_id,
            'fase_id' => $fase_id,
            'kurikulum_id' => $kurikulum_id,
        ]);

        broadcast(new SyllabusCrud('mapel', 'create', $data))->toOthers();

        return redirect()->back()->with('success-insert-data-mapel', 'Mata pelajaran Berhasil Ditambahkan');
    }

    public function mapelUpdate(Request $request, $id, $kelas_id)
    {
        $dataMapel = Mapel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'mata_pelajaran' => [
                'required', Rule::unique('mapels', 'mata_pelajaran')->where('kelas_id', $kelas_id)
            ],
        ], [
            'mata_pelajaran.required' => 'Harap masukkan nama mata pelajaran!',
            'mata_pelajaran.unique' => 'Mata pelajaran telah terdaftar!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422); // Gunakan 422 Unprocessable Entity untuk validasi
        }

        $dataMapel->update([
            'mata_pelajaran' => $request->mata_pelajaran,
            'kode' => $request->mata_pelajaran,
        ]);

        broadcast(new SyllabusCrud('mapel', 'update', $dataMapel))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Mata Pelajaran berhasil diubah.',
            'data' => $dataMapel
        ]);
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

        broadcast(new SyllabusCrud('mapel', 'activate', $dataMapel))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Status Mata Pelajaran Berhasil Diubah',
            'data' => $dataMapel
        ]);
    }

    public function mapelDelete($id)
    {
        $dataMapel = Mapel::findOrFail($id);

        // kalau datanya di delete, berarti harus kaya gini
        $deletedData = $dataMapel->toArray();

        // mendengarkan event listener ketika menghapus mapel
        broadcast(new SyllabusCrud('mapel', 'delete', $deletedData))->toOthers();

        // menghapus mata pelajaran beserta relasi nya (bab, subBab)
        $dataMapel->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Mata Pelajaran berhasil dihapus.',
            'data' => $dataMapel
        ]);
    }

    public function bab($nama_kurikulum, $kurikulum_id, $fase_id, $kelas_id, $mapel_id)
    {
        $dataBab = Bab::with('BabFeatureStatuses', 'Fase', 'Kelas', 'Mapel')->where('kelas_id', $kelas_id)->where('mapel_id', $mapel_id)
        ->where('fase_id', $fase_id)->where('kurikulum_id', $kurikulum_id)->get();

        $dataFeaturesRoles = FeaturesRoles::with('Features')->where('feature_role', 'syllabus')->get();

        // Siapkan mapping id bab + feature_id => status
        $statusBabFeature = [];

        foreach ($dataBab as $bab) {
            foreach ($bab->BabFeatureStatuses as $featureStatus) {
                $statusBabFeature[$bab->id][$featureStatus->feature_id] = $featureStatus->status_bab;
            }
        }

        return view('syllabus-services.list-bab', compact('nama_kurikulum', 'kurikulum_id', 'fase_id', 'kelas_id', 'mapel_id', 'dataBab', 'dataFeaturesRoles', 'statusBabFeature'));
    }

    public function babStore(Request $request, $nama_kurikulum, $kurikulum_id, $fase_id, $kelas_id, $mapel_id)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'nama_bab' => [
                'required',
                Rule::unique('babs', 'nama_bab')->where('kelas_id', $kelas_id)->where('kurikulum_id', $kurikulum_id)->where('mapel_id', $mapel_id)
            ],
            'semester' => 'required',
        ], [
            'nama_bab.required' => 'Harap masukkan bab!',
            'nama_bab.unique' => 'Bab telah terdaftar!',
            'semester.required' => 'Harap pilih semester!',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('formError', 'create')->withInput();
        }

        $data = Bab::create([
            'user_id' => $user->id,
            'nama_bab' => $request->nama_bab,
            'semester' => $request->semester,
            'kode' => $request->nama_bab,
            'kelas_id' => $kelas_id,
            'mapel_id' => $mapel_id,
            'fase_id' => $fase_id,
            'kurikulum_id' => $kurikulum_id,
        ]);

        broadcast(new SyllabusCrud('bab', 'create', $data))->toOthers();

        return redirect()->back()->with('success-insert-data-bab', 'Bab Berhasil Ditambahkan');
    }

    public function babUpdate(Request $request, $kurikulum_id, $kelas_id, $mapel_id, $id)
    {
        $dataBab = Bab::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_bab' => [
                'required',
                Rule::unique('babs', 'nama_bab')->where('kelas_id', $kelas_id)->where('mapel_id', $mapel_id)->where('kurikulum_id', $kurikulum_id)
            ],
            'semester' => 'required',
        ], [
            'nama_bab.required' => 'Harap masukkan bab!',
            'nama_bab.unique' => 'Bab telah terdaftar!',
            'semester.required' => 'Harap pilih semester!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422); // Gunakan 422 Unprocessable Entity untuk validasi
        }

        $dataBab->update([
            'nama_bab' => $request->nama_bab,
            'semester' => $request->semester,
            'kode' => $request->nama_bab,
        ]);

        broadcast(new SyllabusCrud('bab', 'update', $dataBab))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Bab berhasil diubah.',
            'data' => $dataBab
        ]);
    }

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

        broadcast(new SyllabusCrud('bab', 'activate', $dataBabFeatureStatus))->toOthers();

        return response()->json(['message' => 'Status berhasil diperbarui']);
    }
    public function babDelete($id)
    {
        $dataBab = Bab::findOrFail($id);

        // kalau datanya di delete, berarti harus kaya gini
        $deletedData = $dataBab->toArray();

        // mendengarkan event listener ketika menghapus bab
        broadcast(new SyllabusCrud('bab', 'delete', $deletedData))->toOthers();

        $dataBab->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Bab berhasil dihapus.',
            'data' => $dataBab
        ]);
    }

    public function subBab($nama_kurikulum, $kurikulum_id, $fase_id, $kelas_id, $mapel_id, $bab_id)
    {
        $dataSubBab = SubBab::with('SubBabFeatureStatuses')->where('kelas_id', $kelas_id)->where('mapel_id', $mapel_id)->where('fase_id', $fase_id)->where('bab_id', $bab_id)->get();

        $dataFeaturesRoles = FeaturesRoles::with('Features')->where('feature_role', 'syllabus')->get();

        $statusSubBabFeature = [];

        foreach ($dataSubBab as $subBab) {
            foreach ($subBab->SubBabFeatureStatuses as $featureStatus) {
                $statusSubBabFeature[$subBab->id][$featureStatus->feature_id] = $featureStatus->status_sub_bab;
            }
        }

        return view('syllabus-services.list-sub-bab', compact( 'nama_kurikulum', 'kurikulum_id', 'fase_id', 'kelas_id', 'mapel_id',  'bab_id', 'dataSubBab', 'dataFeaturesRoles', 'statusSubBabFeature'));
    }

    public function subBabStore(Request $request, $nama_kurikulum, $kurikulum_id, $fase_id, $kelas_id, $mapel_id, $bab_id)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'sub_bab' => [
                'required',
                Rule::unique('sub_babs', 'sub_bab')->where('kelas_id', $kelas_id)->where('kurikulum_id', $kurikulum_id)->where('mapel_id', $mapel_id)->where('bab_id', $bab_id)
            ],
        ], [
            'sub_bab.required' => 'Harap masukkan sub bab!',
            'sub_bab.unique' => 'Sub Bab telah terdaftar!',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('formError', 'create')->withInput();
        }

        $data = SubBab::create([
            'user_id' => $user->id,
            'sub_bab' => $request->sub_bab,
            'kode' => $request->sub_bab,
            'bab_id' => $bab_id,
            'kelas_id' => $kelas_id,
            'mapel_id' => $mapel_id,
            'fase_id' => $fase_id,
            'kurikulum_id' => $kurikulum_id,
        ]);

        broadcast(new SyllabusCrud('subBab', 'delete', $data))->toOthers();

        return redirect()->back()->with('success-insert-data-sub-bab', 'Sub Bab Berhasil Ditambahkan');
    }


    public function subBabUpdate(Request $request, $kurikulum_id, $kelas_id, $mapel_id, $bab_id, $id)
    {
        $dataSubBab = SubBab::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'sub_bab' => [
                'required',
                Rule::unique('sub_babs', 'sub_bab')->where('kelas_id', $kelas_id)->where('kurikulum_id', $kurikulum_id)->where('mapel_id', $mapel_id)->where('bab_id', $bab_id)
            ],
        ], [
            'sub_bab.required' => 'Harap masukkan sub bab!',
            'sub_bab.unique' => 'Sub Bab telah terdaftar!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422); // Gunakan 422 Unprocessable Entity untuk validasi
        }

        $dataSubBab->update([
            'sub_bab' => $request->sub_bab,
            'kode' => $request->sub_bab,
        ]);

        broadcast(new SyllabusCrud('subBab', 'update', $dataSubBab))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Sub Bab berhasil diubah.',
            'data' => $dataSubBab
        ]);
    }

    public function subBabActivate(Request $request, $id)
    {
        $request->validate([
            'status_sub_bab' => 'required|in:publish,unpublish',
            'feature_id' => 'required|exists:features,id', // Pastikan feature_id valid
        ]);

        // Cari status yang sesuai untuk bab dan fitur
        $dataSubBabFeatureStatus = SubBabFeatureStatus::where('sub_bab_id', $id)
            ->where('feature_id', $request->feature_id) // Pastikan feature_id diberikan
            ->first();

        if ($dataSubBabFeatureStatus) {
            // Jika data sudah ada, hanya update statusnya
            $dataSubBabFeatureStatus->update([
                'status_sub_bab' => $request->status_sub_bab
            ]);
        } else {
            // Jika data tidak ada, buat data baru
            SubBabFeatureStatus::create([
                'sub_bab_id' => $id,
                'feature_id' => $request->feature_id,
                'status_sub_bab' => $request->status_sub_bab
            ]);
        }

        broadcast(new SyllabusCrud('subBab', 'activate', $dataSubBabFeatureStatus))->toOthers();


        return response()->json(['message' => 'Status berhasil diperbarui']);
    }
    public function subBabDelete($id)
    {
        $dataSubBab = SubBab::findOrFail($id);

        // kalau datanya di delete, berarti harus kaya gini
        $deletedData = $dataSubBab->toArray();

        // mendengarkan event listener ketika menghapus bab
        broadcast(new SyllabusCrud('subBab', 'delete', $deletedData))->toOthers();

        $dataSubBab->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sub Bab berhasil dihapus.',
            'data' => $dataSubBab
        ]);
    }

    // FUNCTION BULKUPLOAD SYLLABUS (EXCEL)
    public function bulkUploadSyllabus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bulkUpload-syllabus' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'bulkUpload-syllabus.required' => 'File tidak boleh kosong.',
            'bulkUpload-syllabus.mimes' => 'Format file harus .xlsx.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => [
                    'form_errors' => $validator->errors(),
                    'excel_validation_errors' => [],
                ]
            ], 422);
        }

        try {
            $userId = Auth::id();
            Excel::import(new SyllabusSheetImport($userId, $request->file('bulkUpload-syllabus')), $request->file('bulkUpload-syllabus'));

            return response()->json([
                'status' => 'success',
                'message' => 'Import syllabus berhasil.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => [
                    'form_errors' => [],
                    'excel_validation_errors' => $e->errors()['import'] ?? [],
                ]
            ], 422);
        }
    }

}