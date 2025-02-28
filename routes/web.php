<?php

use App\Models\Web;
use App\Models\Post;
use Illuminate\Support\Arr;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\StarController;
use App\Http\Controllers\testController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\TanyaController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\CatatanController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EnglishZoneController;
use App\Http\Controllers\AuthController; // login daftar
use App\Http\Controllers\CrudController; // database client
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ScrapperController;
use App\Http\Controllers\SuratPKSController;
use App\Http\Controllers\UsersController; //database client (percobaan)
use App\Http\Controllers\VisitasiDataController;
use App\Http\Controllers\webController; // data biasa seperti foreach (tidak dari database) dan lain lain (jika ada selain foreach)

Route::get('/', [webController::class, 'index'])->name('homePage');
Route::get('/guru', [webController::class, 'guru']);

Route::get('/sekolah', function() {
    return view('sekolah', ['title' => 'Sekolah']);
});

Route::get('/murid', function () {
    return view('murid', ['title' => 'Murid']);
});

Route::get('/post', function () {
    return view('post', ['posts' => Post::all()]); //mengambil data dengan satu kali panggil "Post (class) all(method static)"
});

Route::get('/posts/{post:slug}', function (Post $post) { //mengembalikan kolom selain id, bisa tuliskan parameter seperti di samping, post:slug
    return view('viewPost', ['title' => 'single post', 'post' => $post]);
});

Route::get('/histori', function () {
    return view('histori-pembelian');
});

Route::get('/certif', function () {
    return view('certif');
});

Route::get('/about', function () {
    return view('about');
});

Route::fallback(function () {
    return redirect()->route('homePage');
});

Route::get('/input-sekolah', [webController::class, 'inputDataSekolah']);

Route::get('/getCertificate', [CertificateController::class, 'generateCertificate'])->name('generateCertificate');
Route::post('/certificate', [CertificateController::class, 'certificateStore'])->name('certificate.store');
// ROUTES CRUD
Route::get('/crud', [CrudController::class, 'index'])->name('crud');
Route::get('/crud/create', [CrudController::class, 'create'])->name('crud.create');
Route::post('/crud', [CrudController::class, 'store'])->name('crud.store');
Route::delete('/crud/destroy/{id}', [CrudController::class, 'destroy'])->name('crud.destroy');
Route::get('/crud/{id}/edit', [CrudController::class, 'edit'])->name('crud.edit');
Route::put('/crud/update/{id}', [CrudController::class, 'update'])->name('crud.update');

// ROUTES AUTH
Route::get('/daftar', fn() => view('Auth.daftar'))->name('daftar');
Route::post('/daftar', [AuthController::class, 'daftar']);
Route::get('/login', fn() => view('Auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/beranda', [webController::class, 'beranda'])->name('beranda'); // keamanan (jika belum login tidak dapat mengakses beranda)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ROUTES DROPDOWN FASE, KELAS, MAPEL, BAB (AJAX)
Route::get('/kelas/{id}', [KelasController::class, 'getKelas']);
Route::get('/mapel/{id}', [KelasController::class, 'getMapel']);
Route::get('/bab/{id}', [KelasController::class, 'getBab']);

Route::get('fase', [KelasController::class, 'fase'])->name('fase.index');
Route::get('kelas/{id}', [KelasController::class, 'kelas']);
Route::get('/daftar', [KelasController::class, 'fase'])->name('daftar');

// ROUTES END TO END TANYA (SISWA DAN MENTOR)
Route::get('/tanya', [TanyaController::class, 'index'])->name('tanya');
Route::post('/tanya', [TanyaController::class, 'store'])->name('tanya.store');
Route::get('/view/{id}', [TanyaController::class, 'edit'])->name('tanya.edit');
Route::get('/view/restore/{id}', [TanyaController::class, 'viewRestore'])->name('getRestore.edit');
Route::put('/view/updateAnswer/{id}', [TanyaController::class, 'update'])->name('tanya.update');
Route::put('/view/updateReject/{id}', [TanyaController::class, 'updateReject'])->name('tanya.reject');
Route::post('/tanya/{id}/restore', [TanyaController::class, 'restore'])->name('tanya.restore');
Route::get('/filter', [FilterController::class, 'filterHistoryStudent'])->name('filter.index');
Route::get('/filterTeacher', [FilterController::class, 'filterHistoryTeacher'])->name('filter.fill');
Route::get('/paginateTanyaTeacher', [FilterController::class, 'filterTanyaTeacher'])->name('tanya.teacher');
Route::get('/paginateTanyaTL', [FilterController::class, 'filterTanyaTL'])->name('tanya.TL');

Route::get('/paginateListMentor', [filterController::class, 'filterListMentor']);
Route::get('/paginateViewLaporan', [FilterController::class, 'filterViewLaporanTL']);



// ROUTES CATATAN
Route::get('/catatan', [CatatanController::class, 'index']);
Route::post('/catatan', [CatatanController::class, 'store'])->name('catatan.store');
Route::get('/paginateCatatan', [FilterController::class, 'filterClassNote'])->name('catatan.filter');
Route::get('/paginateMapelCatatan', [FilterController::class, 'filterMapelNote'])->name('mapel.filter');


// ROUTES LAPORAN
Route::post('/star', [StarController::class, 'store'])->name('star.store');
Route::get('/laporan/{id}', [webController::class, 'viewLaporan'])->name('laporan.edit');
Route::get('/laporan', [WebController::class, 'laporan']);


Route::get('/sidebar', [WebController::class, 'sidebarBeranda']);
Route::get('/sidebar-beranda-mobile', [WebController::class, 'sidebarBerandaMobile']);

Route::post('/update-payment-status/{email}/{batch}', [StarController::class, 'updatePaymentStatus'])->name('starPayment.update');


// ROUTES ENGLISH ZONE
// Routes View
Route::get('/english-zone', [EnglishZoneController::class, 'index'])->name('englishZone.index');
Route::get('upload-materi', [WebController::class, 'uploadMateri']);
Route::get('/upload-soal', [webController::class, 'uploadSoal']);
// lalu route akan mendapatkan parameter yang dikirim oleh href tadi yang akan di proses oleh controller
Route::get('/pengayaan/{modul}/{id}', [EnglishZoneController::class, 'pengayaan'])->name('pengayaan');
Route::get('question-for-release', [EnglishZoneController::class, 'questionForRelease']);

// Routes CRUD
Route::post('/upload-materi', [EnglishZoneController::class, 'uploadMateriStore'])->name('englishZone.uploadMateri');
Route::post('/upload-soal', [EnglishZoneController::class, 'uploadSoalStore'])->name('englishZone.uploadSoal');
Route::get('/englishZone-view/{id}', [EnglishZoneController::class, 'show'])->name('englishZone.show');

Route::post('/laporana', [EnglishZoneController::class, 'uploadImage'])->name('englishZone.uploadImage');
Route::post('/delete-image-endpoint', [EnglishZoneController::class, 'deleteImage'])->name('englishZone.deleteImage');

Route::post('/pengayaan/{id}', [EnglishZoneController::class, 'uploadJawaban'])->name('englishZoneJawaban.store');

Route::put('question-for-release/update', [englishZoneController::class, 'update'])->name('questionForRelease.update');

Route::get('/filter-questions', [filterController::class, 'questionStatus'])->name('filter.questions');

Route::get('video/{modul}', [EnglishZoneController::class, 'video'])->name('englishZone.video');

// CHART CONTROLLER
Route::get('/chart-data-tanya-bulanan', [ChartController::class, 'chartTanyaBulanan'])->name('getChartDataTanyaBulanan');
Route::get('/chart-data-tanya-tahunan', [ChartController::class, 'chartTanyaTahunan'])->name('getChartDataTanyaTahunan');
Route::get('/chart-data-tanya-harian', [ChartController::class, 'chartTanyaHarian'])->name('getChartDataTanyaHarian');


// ROUTES MASTERDATA(KERJASAMA SEKOLAH B2B & B2G)
// VIEW
Route::get('/upload-surat-pks', [SuratPKSController::class, 'index'])->name('suratPKS');
// CRUD
Route::post('/suratPKS', [suratPKSController::class, 'uploadSuratPKS'])->name('suratPKS.store');
Route::post('/inputDataSekolah', [MasterDataController::class, 'inputDataSekolah'])->name('inputDataSekolah.store');
// SHOW PDF
Route::get('/surat-pks-english-zone', [SuratPKSController::class, 'generateSuratPKSEnglishZone'])->name('generateSuratPKSEnglishZone');

// ROUTES VISITASIDATA (KERJASAMA SEKOLAH B2B & B2G) (Sales)
// VIEW
Route::get('/visitasi/jadwal-kunjungan', [VisitasiDataController::class, 'jadwalKunjungan'])->name('jadwalKunjungan');
Route::get('/visitasi/data-kunjungan', [VisitasiDataController::class, 'dataKunjungan'])->name('dataKunjungan');
Route::get('/visitasi/cetak-pks', [VisitasiDataController::class, 'cetakPKS'])->name('cetakPKS');
// CRUD
Route::post('/visitasiData', [VisitasiDataController::class, 'visitasiDataStore'])->name('visitasiData.store');
Route::put('/visitasiData/{id}', [VisitasiDataController::class, 'updateStatusKunjungan'])->name('visitasiData.update');

// ROUTES TESTING
Route::get('/select', [BarangController::class, 'index']);
Route::get('/barang/{id}', [BarangController::class, 'getBarang']);

Route::get('/test', [testController::class, 'index'])->name('test');
Route::get('/test/create', [testController::class, 'create'])->name('test.create');
Route::post('/test', [testController::class, 'store'])->name('test.store');
Route::post('/test/delete', [testController::class, 'destroy'])->name('test.destroy');
Route::resource('test', testController::class);
Route::post('/test/{id}/restore', [testController::class, 'restore'])->name('test.restore');

Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
Route::get('/modules/{id}', [ModuleController::class, 'show'])->name('modules.show');
Route::post('/modules/{id}/complete', [ModuleController::class, 'complete'])->name('modules.complete');