<?php

namespace App\Http\Controllers;

use App\Events\SchoolPartnerUserSubscription;
use App\Imports\SchoolPartnerSheetImport;
use App\Models\FeatureSubscriptionHistory;
use App\Models\SchoolPartner;
use App\Models\UserAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class SchoolPartnerController extends Controller
{
    // function untuk menampilkan halaman list school partner
    public function schoolSubscriptionView()
    {
        return view('school-partner.list-school-subscription');
    }

    // function untuk bulk upload school partner
    public function bulkUploadSchoolPartner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bulkUpload-school-partner' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'bulkUpload-school-partner.required' => 'File tidak boleh kosong.',
            'bulkUpload-school-partner.mimes' => 'Format file harus .xlsx.',
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
            Excel::import(new SchoolPartnerSheetImport($userId, $request->file('bulkUpload-school-partner')), $request->file('bulkUpload-school-partner'));

            return response()->json([
                'status' => 'success',
                'message' => 'Import school partner berhasil.',
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

    // function paginate list school partner
    public function paginateListSchoolPartner(Request $request)
    {
        $listSchoolPartner = SchoolPartner::orderBy('created_at', 'desc')->get();

        // Ambil hanya 1 data per sekolah (first dari tiap group)
        $groupedSchool = $listSchoolPartner->groupBy('nama_sekolah')->map(function ($group) {
            return $group->first();
        });

        // Filter school
        if ($request->filled('search_school')) {
            $search = $request->search_school;
            $groupedSchool = $groupedSchool->filter(function ($item) use ($search) {
                return Str::contains(strtolower($item->nama_sekolah), strtolower($search));
            });
        }

        // Pagination manual
        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $pagedData = $groupedSchool->slice($offset, $perPage)->values();

        $paginated = new LengthAwarePaginator(
            $pagedData,
            $groupedSchool->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return response()->json([
            'data' => $paginated->values(), // flat array, bukan nested
            'links' => (string) $paginated->links(),
            'current_page' => $paginated->currentPage(),
            'per_page' => $paginated->perPage(),
            'listUserSchoolSubscription' => '/school-subscription/:schoolId/user',
        ]);
    }

    // function untuk menampilkan halaman list user school subscription
    public function userSchoolSubscriptionView($schoolId)
    {
        $getFeatureSubscriptionHistory = FeatureSubscriptionHistory::with(['Transactions.Features', 'UserAccount.StudentProfiles', 'UserAccount.StudentProfiles.Fase', 'UserAccount.StudentProfiles.Kelas'])
        ->whereHas('UserAccount.StudentProfiles', function ($query) use ($schoolId) {
            $query->where('sekolah', $schoolId)->whereRaw("substr(email, -length(?)) = ?", ['@belajarcerdas.id', '@belajarcerdas.id']);
        })->get()->groupBy('student_id');

        $countFeatures = $getFeatureSubscriptionHistory
        ->flatten() // gabung semua item
        ->pluck('Transactions.Features.nama_fitur') // ambil nama fitur
        ->unique(); // hilangkan duplikat

        return view('school-partner.list-user-school-subscription', compact('schoolId', 'getFeatureSubscriptionHistory', 'countFeatures'));
    }

    // function paginate list user school subscription
    public function paginateListUserSchoolSubscription(Request $request, $schoolId)
    {
        $today = now()->format('Y-m-d');

        $schoolIdentity = SchoolPartner::where('nama_sekolah', $schoolId)->first();

        $getFeatureSubscriptionHistory = FeatureSubscriptionHistory::with(['Transactions.Features', 'UserAccount.StudentProfiles', 'UserAccount.StudentProfiles.Fase', 'UserAccount.StudentProfiles.Kelas'])
        ->whereHas('UserAccount.StudentProfiles', function ($query) use ($schoolId, $request) {
            $query->where('sekolah', $schoolId)->whereRaw("substr(email, -length(?)) = ?", ['@belajarcerdas.id', '@belajarcerdas.id']); // ambil bagian akhir email @belajarcerdas.id saja

            // Filter jika ada student
            if ($request->filled('search_student')) {
                $query->where('nama_lengkap', 'like', '%' . $request->search_student . '%'); // filter nama sesuai request (jika search a, muncul nama yang mengandung a)
            }
        })->whereDate('end_date', '>=', $today)->get();

        $groupedData = $getFeatureSubscriptionHistory->groupBy('student_id');

        // Pagination manual
        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $pagedData = $groupedData->slice($offset, $perPage)->values();

        $paginated = new LengthAwarePaginator(
            $pagedData,
            $groupedData->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $getUsers = UserAccount::whereHas('StudentProfiles', function ($query) use ($schoolId) {
            $query->where('sekolah', $schoolId);
        })->pluck('id');

        $getSubscription = FeatureSubscriptionHistory::whereDate('end_date', '<', $today)->whereIn('student_id', $getUsers)->where('subscription_status', 'aktif')
        ->get();

        foreach ($getSubscription as $subscription) {
            $subscription->update([
                'subscription_status' => 'tidak_aktif'
            ]);
        }

        return response()->json([
            'data' => $paginated->items(),
            'links' => (string) $paginated->links(),
            'schoolIdentity' => $schoolIdentity,
        ]);
    }

    // function untuk aktif dan menonaktifkan fitur by student
    public function activateFeatureByStudent(Request $request, $id)
    {
        $request->validate([
            'subscription_status' => 'required|in:aktif,tidak_aktif'
        ]);

        $featureSubscriptionHistory = FeatureSubscriptionHistory::findOrFail($id);

        $featureSubscriptionHistory->update([
            'subscription_status' => $request->subscription_status
        ]);

        broadcast(new SchoolPartnerUserSubscription($featureSubscriptionHistory))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Status fitur berhasil diubah.',
            'data' => $featureSubscriptionHistory
        ]);
    }

    // function untuk aktif dan menonaktifkan fitur all students by school
    public function activateFeatureForAllStudents(Request $request, $schoolId, $featureId)
    {
        $request->validate([
            'subscription_status' => 'required|in:aktif,tidak_aktif'
        ]);

        // ambil semua siswa yang ada di sekolah $schoolId tersebut
        $getStudents = UserAccount::whereHas('StudentProfiles', function ($query) use ($schoolId) {
            $query->where('sekolah', $schoolId)->whereRaw("substr(email, -length(?)) = ?", ['@belajarcerdas.id', '@belajarcerdas.id']); // ambil bagian akhir email @belajarcerdas.id saja
        })->pluck('id');

        // ambil fitur yang dibeli oleh sekolah tersebut lalu cocokkan dengan $featureId
        $featureSubscriptionHistory = FeatureSubscriptionHistory::whereHas('Transactions', function ($query) use ($featureId) {
            $query->where('feature_id', $featureId);
        })->whereIn('student_id', $getStudents)->get();

        // update status fitur
        foreach ($featureSubscriptionHistory as $subscription) {
            $subscription->update([
                'subscription_status' => $request->subscription_status
            ]);
        }

        broadcast(new SchoolPartnerUserSubscription($featureSubscriptionHistory))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => 'Status fitur berhasil diubah.',
            'data' => $featureSubscriptionHistory
        ]);
    }
}