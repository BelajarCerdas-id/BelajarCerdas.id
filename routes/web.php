<?php

use App\Models\Web;
use App\Models\Post;
use Illuminate\Support\Arr;
use App\Http\Controllers\User;
use App\Http\Middleware\TanyaAccess;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PksController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\MasterAcademicController;
use App\Http\Controllers\TanyaController;
use App\Http\Middleware\CheckEnglishZone;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScrapperController;
use App\Http\Controllers\SuratPKSController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EnglishZoneController;
use App\Http\Controllers\MitraCerdasController;
use App\Http\Controllers\VisitasiDataController;
use App\Http\Controllers\AuthController; // login daftar
use App\Http\Controllers\PaymentFeaturesController;
use App\Http\Controllers\SoalPembahasanController;
use App\Http\Controllers\webController; // data biasa seperti foreach (tidak dari database) dan lain lain (jika ada selain foreach)

Route::get('/', [webController::class, 'index'])->name('homePage');
Route::get('/mitra-cerdas', [webController::class, 'mitraCerdas'])->name('mitraCerdas');
Route::get('/siswa', [webController::class, 'siswa'])->name('siswa');

Route::get('/sekolah', function() {
    return view('sekolah', ['title' => 'Sekolah']);
});

Route::get('/murid', function () {
    return view('murid', ['title' => 'Murid']);
});

Route::get('/certif', function () {
    return view('certif');
});

Route::get('/about', [webController::class, 'about'])->name('about');


Route::fallback(function () {
    return redirect()->route('homePage');
});



        // ROUTES AUTH
        // ROUTES VIEWS REGISTER STUDENT & MENTOR
        Route::get('/daftar-mentor', [AuthController::class, 'registerMentor'])->name('daftar.mentor');
        Route::get('/daftar', [AuthController::class, 'indexRegister'])->name('daftar.user');
        Route::get('/daftar-siswa', [AuthController::class, 'registerStudent'])->name('daftar.siswa');

        // CRUD STUDENT
        Route::post('/register/validate-step/student', [AuthController::class, 'validateStepFormStudent'])->name('register.validateStepFormStudent');
        Route::post('/register/student/store', [AuthController::class, 'registerStudentStore'])->name('registerStudent.store');

        // CRUD MENTOR
        Route::post('/register/validate-step/mentor', [AuthController::class, 'validateStepFormMentor'])->name('register.validateStepFormMentor');
        Route::post('/register/mentor/store', [AuthController::class, 'registerMentorStore'])->name('registerMentor.store');

        // ROUTES OTP REGISTER
        Route::post('/register/send-otp-mail/student', [AuthController::class, 'sendOtpMailStudent'])->name('register.sendOtpMailStudent');
        Route::post('/register/send-otp-mail/mentor', [AuthController::class, 'sendOtpMailMentor'])->name('register.sendOtpMailMentor');

        // ROUTES LOGIN
        Route::get('/login', fn() => view('Auth.login'))->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

        // ROUTE LOGOUT
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // ROUTE PAYMENT FEATURES VIEW
        Route::get('/pembayaran-fitur/{nama_fitur}', [PaymentFeaturesController::class, 'paymentFeaturesView'])->name('paymentFeaturesView');

        // COIN CHECKOUT (TANYA)
        // Route::get('/tanya-coin', [TanyaController::class, 'coinPackage'])->name('coinPackage');
        Route::post('/checkout', [PaymentFeaturesController::class, 'checkoutCoinTanya'])->name('checkout');
        Route::post('/renew-checkout/{id}', [PaymentFeaturesController::class, 'renewCheckoutCoinTanya'])->name('checkout.pending');

        // ROUTES DROPDOWN FASE, KELAS, MAPEL, BAB (AJAX)
        Route::get('/kelas/{id}', [MasterAcademicController::class, 'getKelas']); // kelas by fase
        Route::get('/kurikulum/kelas/{id}', [MasterAcademicController::class, 'getKelasByKurikulum']); // kelas by kurikulum

        Route::get('/mapel/{id}', [MasterAcademicController::class, 'getMapel']); // mapel by fase
        Route::get('/kelas/mapel/{id}', [MasterAcademicController::class, 'getMapelByKelas']); // mapel by kelas

        Route::get('tanya/bab/{kode_mapel}', [MasterAcademicController::class, 'getBabTanyaFeature']); // mapel by tanya feature
        Route::get('/soal-pembahasan/bab/{kode_mapel}', [MasterAcademicController::class, 'getBabSoalPembahasanFeature']); // mapel by soal pembahasan feature

        Route::get('/sub-bab/{kode_bab}', [MasterAcademicController::class, 'getSubBabSoalPembahasanFeature']); // bab by soal pembahasan feature

        // CHART CONTROLLER (BERANDA ADMINISTRATOR)
        Route::get('/chart-data-tanya-bulanan', [ChartController::class, 'chartTanyaBulanan'])->name('getChartDataTanyaBulanan');
        Route::get('/chart-data-tanya-tahunan', [ChartController::class, 'chartTanyaTahunan'])->name('getChartDataTanyaTahunan');
        Route::get('/chart-data-tanya-harian', [ChartController::class, 'chartTanyaHarian'])->name('getChartDataTanyaHarian');

    // MIDDLEWARE LOGIN
    Route::middleware([AuthMiddleware::class])->group(function () {
        // BERANDA
        Route::get('/beranda', [webController::class, 'beranda'])->name('beranda');

        // HISTORY PEMBELIAN
        // View
        Route::get('/histori-pembelian', [webController::class, 'historiPembelian'])->name('historiPembelian.index');
        Route::get('/histori-koin', [webController::class, 'historiKoin'])->name('historiKoin.index');

        // paginate history pembelian
        Route::get('/paginate-histori-pembelian-success', [FilterController::class, 'paginateHistoryPurchaseSuccess'])->name('historiPembelianSuccess.paginate');
        Route::get('/paginate-histori-pembelian-waiting', [FilterController::class, 'paginateHistoryPurchaseWaiting'])->name('historiPembelianWaiting.paginate');
        Route::get('/paginate-histori-pembelian-failed', [FilterController::class, 'paginateHistoryPurchaseFailed'])->name('historiPembelianFailed.paginate');

        // paginate history koin
        Route::get('/paginate-histori-koin-masuk', [FilterController::class, 'paginateHistoryCoinIn'])->name('historiKoinMasuk.paginate');
        Route::get('/paginate-histori-koin-keluar', [FilterController::class, 'paginateHistoryCoinOut'])->name('historiKoinKeluar.paginate');

        // PROFILE USER
        // View
        Route::get('/profile', [ProfileController::class, 'profileUser'])->name('profile');
        Route::get('/atur-ulang-sandi', [ProfileController::class, 'aturUlangSandi'])->name('aturUlangSandi');

        // CRUD edit profile user
        // Student
        Route::put('/update-personal-information-student/{id}', [ProfileController::class, 'updatePersonalInformationStudent'])->name('updatePersonalInformationStudent.update');
        Route::put('/update-pendidikan-student/{id}', [ProfileController::class, 'updatePendidikanStudent'])->name('updatePendidikanStudent.update');

        //Mentor
        Route::put('/update-personal-information-mentor/{id}', [ProfileController::class, 'updatePersonalInformationMentor'])->name('updatePersonalInformationMentor.update');
        Route::put('/update-pendidikan-mentor/{id}', [ProfileController::class, 'updatePendidikanMentor'])->name('updatePendidikanMentor.update');

        // Administrator
        Route::put('/update-personal-information-administrator/{id}', [ProfileController::class, 'updatePersonalInformationAdministrator'])->name('updatePersonalInformationAdministrator.update');

        // CRUD atur ulang sandi
        Route::put('/atur-ulang-sandi-update/{id}', [ProfileController::class, 'AturUlangSandiUpdate'])->name('aturUlangSandi.update');

        // CRUD REFERRAL CODE MENTOR (student)
        Route::put('/referral-code-student/{id}', [ProfileController::class, 'referralCodeStudent'])->name('referralCodeStudent.update');

        // UPDATE COIN USER WHEN QUESTION REJECTED (with pusher)
        Route::get('/update-koin-student', [TanyaController::class, 'getKoinStudent'])->name('tanya.getKoinStudent');

        // FILTER LEADERBOARD RANK TANYA STUDENT
        Route::get('/leaderboard-rank-tanya-student', [FilterController::class, 'leaderboardRankTanya'])->name('filterLeaderboardRankTanyaStudent');

    // TANYA ACCESS MIDDLEWARE
    Route::middleware([TanyaAccess::class])->group(function () {
        // VIEWS
        Route::get('/tanya', [TanyaController::class, 'index'])->name('tanya.index'); // page tanya (siswa & murid & mentor)
        Route::get('/view/{id}', [TanyaController::class, 'edit'])->name('tanya.edit'); // page jawab soal siswa (mentor)
        Route::get('/history/restore/{id}', [TanyaController::class, 'viewRestore'])->name('getRestore.edit'); // page riwayat tanya siswa (siswa & murid)
        Route::get('/restore/{id}', [TanyaController::class, 'updateStatusSoalRestore'])->name('tanya.updateStatusSoalRestore');

        // CRUD TANYA
        // Siswa
        Route::post('/tanya/store', [TanyaController::class, 'store'])->name('tanya.store');
        // Mentor
        Route::put('/updateAnswer/{id}', [TanyaController::class, 'update'])->name('tanya.update'); // page update jawab soal siswa (mentor)
        Route::put('/updateReject/{id}', [TanyaController::class, 'updateReject'])->name('tanya.reject'); // page update tolak soal siswa (mentor)
        Route::post('/history/{id}/restore', [TanyaController::class, 'restore'])->name('tanya.restore');

        // UPDATE STATUS SOAL ANSWERED & REJECTED
        Route::put('updateStatusSoalAnswered/{id}', [TanyaController::class, 'markQuestionAsReadById'])->name('tanya.updateStatusSoalById');
        Route::put('updateAllStatusSoalAnswered/{id}', [TanyaController::class, 'markAllQuestionsAsReadById'])->name('tanya.updateAllStatusSoalById');
        Route::put('updateAllStatusSoalRejected/{email}', [TanyaController::class, 'markAllQuestionsRejectedAsReadById'])->name('tanya.updateAllStatusSoalRejectedById');

        // FILTERING & PAGINATE HISTORY TANYA (student)
        Route::get('/filter', [FilterController::class, 'filterHistoryStudent'])->name('filter.index');
        Route::get('/filterTeacher', [FilterController::class, 'filterHistoryTeacher'])->name('filter.fill');
        Route::get('/paginateTanyaTeacher', [FilterController::class, 'filterTanyaTeacher'])->name('tanya.teacher');
        Route::get('/paginateTanyaTL', [FilterController::class, 'filterTanyaTL'])->name('tanya.TL');

        // HISTORY CONTENT DAILY TANYA ANSWERED & REJECTED (student)
        Route::get('/student/history-unanswered', [TanyaController::class, 'getHistoryUnansweredTanya'])->name('tanya.historyUnAnswered');
        Route::get('/student/history-answered', [TanyaController::class, 'getHistoryAnsweredTanya'])->name('tanya.historyAnswered');
        Route::get('/student/history-rejected', [TanyaController::class, 'getHistoryRejectedTanya'])->name('tanya.historyRejected');

        Route::post('/tanya/{id}/mark-viewed', [TanyaController::class, 'markViewed'])->name('tanya.markViewed');
        Route::put('/tanya/{id}/mark-viewed-back-button', [TanyaController::class, 'markViewedBackButton'])->name('tanya.markViewedBackButton');
        Route::post('/tanya/{id}/mark-viewed-back-button', [TanyaController::class, 'markViewedBackButton'])->name('tanya.markViewedBackButton');

        // CLAIM COIN DAILY (student)
        Route::post('/tanya/claim-coin', [TanyaController::class, 'claimCoinDaily'])->name('tanya.claimCoinDaily');

    });

    // TANYA ACCESS CRUD (ADMINISTRATOR)
    Route::get('/tanya/access', [TanyaController::class, 'tanyaAccess'])->name('tanya.access'); // page tanya access
    Route::post('/tanya-access-store', [TanyaController::class, 'tanyaAccessStore'])->name('tanyaAccessStore'); // insert data tanya access
    Route::post('/tanya-access-update/{id}', [TanyaController::class, 'updateTanyaAccess'])->name('tanyaAccessUpdate'); // insert data tanya access

    // FILTERING TANYA ACCESS
    Route::get('/filter-tanya-access', [FilterController::class, 'tanyaAccess'])->name('filterTanyaAccess');

    // LIST PERTANYAAN ROLLBACK (ADMINISTRATOR)
    Route::get('/list-pertanyaan', [TanyaController::class, 'listQuestion'])->name('listQuestion.index');

    // PAGINATE PERTANYAAN ROLLBACK
    Route::get('/paginateTanyaRollback', [FilterController::class, 'paginateTanyaRollback'])->name('tanya.rollback');

    // CRUD
    Route::post('/paginateTanyaRollback/update/{id}', [TanyaController::class, 'rollbackQuestion'])->name('rollbackQuestion.update');


    // TANYA RANK (MENTOR)
    Route::get('/rank', [TanyaController::class, 'tanyaRank'])->name('tanya.rank');

    // PAYMENT MENTOR (ADMINISTRATOR)
    // LIST MENTOR TANYA, QUESTION MENTOR TANYA ACCEPTED, PAYMENT MENTOR PAYMENT VIEWS
    Route::get('/mentor-tanya', [TanyaController::class, 'mentorTanya'])->name('tanya.mentor');
    Route::get('/mentor-tanya/verification/{id}', [TanyaController::class, 'questionMentorVerifiedView'])->name('tanya.mentor.accepted.view');
    Route::get('/pembayaran-mentor', [TanyaController::class, 'paymentMentorView'])->name('pembayaran.tanya.mentor.view');

    // PAGINATE LIST MENTOR TANYA & QUESTION MENTOR TANYA ACCEPTED & PAYMENT MENTOR
    Route::get('/paginate/list-mentor-tanya', [FilterController::class, 'paginateListMentorTanya'])->name('paginate.listMentorTanya');
    Route::get('/paginate/verifikasi-pertanyaan-mentor/{mentor_id}', [FilterController::class, 'paginateVerificationTanyaMentor'])->name('paginate.verificationTanyaMentor');
    Route::get('/paginate/list-pembayaran-mentor', [FilterController::class, 'paginateListPaymentTanyaMentor'])->name('paginate.listPaymentTanyaMentor');

    // CRUD VERIFICATION QUESTION MENTOR
    Route::post('/question-mentor-tanya/accepted/{id}', [TanyaController::class, 'questionMentorVerifiedAccepted'])->name('verificationTanyaMentor.accepted');
    Route::post('/question-mentor-tanya/rejected/{id}', [TanyaController::class, 'questionMentorVerifiedRejected'])->name('verificationTanyaMentor.rejected');

    // CRUD PEMBAYARAN TANYA MENTOR
    Route::post('/pembayaran-mentor/update/{id}', [TanyaController::class, 'paymentMentorUpdate'])->name('pembayaran.tanya.mentor.update');

    // ROUTES REPORT USER (laporan student, mentor, dll)
    Route::get('/laporan-mentor', [webController::class, 'reportMentor'])->name('report-mentor');
    Route::get('/batch-detail-pembayaran-mentor/{id}', [webController::class, 'batchDetailPaymentMentor'])->name('batch.detail.payment.mentor');

    // PAGINATE LAPORAN MENTOR
    Route::get('/paginate/report-mentor', [FilterController::class, 'paginateReportPaymentMentor'])->name('paginate.reportPaymentMentor');
    Route::get('/paginate/batch-detail-payment-mentor/{id}', [FilterController::class, 'paginateBatchDetailPaymentMentor'])->name('paginate.batchDetailPaymentMentor');

    // ROUTES SOAL DAN PEMBAHASAN
    // BANK SOAL VIEWS (ADMINISTRATOR)
    Route::get('/soal-pembahasan/bank-soal', [SoalPembahasanController::class, 'bankSoalView'])->name('bankSoal.view');
    Route::get('/soal-pembahasan/bank-soal/{subBab}/{subBabId}', [SoalPembahasanController::class, 'bankSoalDetail'])->name('bankSoal.detail.view');
    Route::get('/soal-pembahasan/bank-soal/{subBab}/{subBabId}/{id}', [SoalPembahasanController::class, 'editQuestionView'])->name('bankSoal.edit.question.view');
    Route::get('/soal-pembahasan/bank-soal/form/{subBab}/{subBabId}/{id}', [SoalPembahasanController::class, 'formEditQuestion'])->name('bankSoal.form.edit.question');
    Route::post('/soal-pembahasan/bank-soal/update/{id}', [SoalPembahasanController::class, 'editQuestion'])->name('bankSoal.edit.question.update');

    // CRUD BANK SOAL (ADMINISTRATOR)
    Route::post('/soal-pembahasan/bank-soal-store', [SoalPembahasanController::class, 'bankSoalStore'])->name('bankSoal.store');
    Route::put('/soal-pembahasan/bank-soal/activate/{subBabId}', [SoalPembahasanController::class, 'bankSoalActivate'])->name('bankSoal.activate');

    // PAGINATE BANK SOAL (ADMINISTRATOR)
    Route::get('/soal-pembahasan/paginate/bank-soal', [FilterController::class, 'paginateBankSoal'])->name('bankSoal.paginate');
    Route::get('/soal-pembahasan/paginate/bank-soal/{subBab}/{subBabId}', [FilterController::class, 'paginateBankSoalDetail'])->name('bankSoalDetail.paginate');

    // UPLOAD & DELETE IMAGE BANK SOAL WITH CKEDITOR
    Route::post('/bank-soal/edit-image', [SoalPembahasanController::class, 'editImageBankSoal'])->name('soalPembahasan.editImage');
    Route::post('/bank-soal/delete-image/endpoint', [SoalPembahasanController::class, 'deleteImageBankSoal'])->name('soalPembahasan.deleteImage');

    // SOAL PEMBAHASAN (STUDENT)
    Route::get('/soal-pembahasan', [SoalPembahasanController::class, 'soalPembahasanKelasView'])->name('soalPembahasanKelas.view');
    Route::get('/soal-pembahasan/{kelas}/{kelas_id}', [SoalPembahasanController::class, 'soalPembahasanMapelView'])->name('soalPembahasanMapel.view');
    Route::get('/soal-pembahasan/{kelas}/{kelas_id}/{mata_pelajaran}/{mapel_id}/bab', [SoalPembahasanController::class, 'soalPembahasanBabView'])->name('soalPembahasanBab.view');
    Route::get('/soal-pembahasan/{kelas}/{kelas_id}/{mata_pelajaran}/{mapel_id}/{bab_id}/sub-bab', [SoalPembahasanController::class, 'soalPembahasanSubBabView'])->name('soalPembahasanSubBab.view');
    Route::get('/soal-pembahasan/{kelas}/{kelas_id}/{mata_pelajaran}/{mapel_id}/{bab_id}/assessment', [SoalPembahasanController::class, 'soalPembahasanAssessmentView'])->name('soalPembahasanAssessment.view');

    // ASSESSMENT
    // PRACTICE VIEW
    Route::get('/soal-pembahasan/kelas/{kelas}/{kelas_id}/{mata_pelajaran}/{mapel_id}/{bab_id}/{sub_bab_id}/assessment/latihan', [SoalPembahasanController::class, 'practice'])->name('soalPembahasanAssessment.practice');

    // PRACTICE QUESTIONS FORM
    Route::get('/soal-pembahasan/kelas/{sub_bab_id}/assessment/latihan', [SoalPembahasanController::class, 'practiceQuestionsForm'])->name('practiceQuestions.form');

    // PRACTICE ANSWER
    Route::post('/soal-pembahasan/kelas/{sub_bab_id}/assessment/latihan/answer', [SoalPembahasanController::class, 'practiceAnswer'])->name('soalPembahasanAssessment.practice.answer');

    // EXAM VIEW
    Route::get('/soal-pembahasan/kelas/{kelas}/{kelas_id}/{mata_pelajaran}/{mapel_id}/{bab_id}/assessment/ujian', [SoalPembahasanController::class, 'exam'])->name('soalPembahasanAssessment.exam');



    //ROUTES SYLLABUS-SERVICES
    // VIEWS
    Route::get('/syllabus/curiculum', [SyllabusController::class, 'curiculum'])->name('kurikulum.index');
    Route::get('/syllabus/curiculum/{nama_kurikulum}/{id}/fase', [SyllabusController::class, 'fase'])->name('fase.index');
    Route::get('/syllabus/curiculum/{nama_kurikulum}/{kurikulum_id}/{fase_id}/kelas', [SyllabusController::class, 'kelas'])->name('kelas.index');
    Route::get('/syllabus/curiculum/{nama_kurikulum}/{kurikulum_id}/{fase_id}/{kelas_id}/mapel', [SyllabusController::class, 'mapel'])->name('mapel.index');
    Route::get('/syllabus/curiculum/{nama_kurikulum}/{kurikulum_id}/{fase_id}/{kelas_id}/{mapel_id}/bab', [SyllabusController::class, 'bab'])->name('bab.index');
    Route::get('/syllabus/curiculum/{nama_kurikulum}/{kurikulum_id}/{fase_id}/{kelas_id}/{mapel_id}/{bab_id}/sub-bab', [SyllabusController::class, 'subBab'])->name('subBab.index');

    // CRUD Kurikulum
    Route::post('/syllabus/curiculum/store', [SyllabusController::class, 'curiculumStore'])->name('kurikulum.store');
    Route::post('/syllabus/curiculum/update/{id}', [SyllabusController::class, 'curiculumUpdate'])->name('kurikulum.update');
    Route::delete('/syllabus/curiculum/delete/{id}', [SyllabusController::class, 'curiculumDelete'])->name('kurikulum.delete');

    // CRUD Fase
    Route::post('/syllabus/{id}/fase/store', [SyllabusController::class, 'faseStore'])->name('fase.store');
    Route::post('/syllabus/curiculum/fase/update/{kurikulum_id}/{id}', [SyllabusController::class, 'faseUpdate'])->name('fase.update');
    Route::delete('/syllabus/curiculum/fase/delete/{id}', [SyllabusController::class, 'faseDelete'])->name('fase.delete');

    // CRUD Kelas
    Route::post('/syllabus/curiculum/{nama_kurikulum}/{kurikulum_id}/{fase_id}/kelas/store', [SyllabusController::class, 'kelasStore'])->name('kelas.store');
    Route::post('/syllabus/curiculum/kelas/update/{kurikulum_id}/{fase_id}/{id}', [SyllabusController::class, 'kelasUpdate'])->name('kelas.update');
    Route::delete('/syllabus/curiculum/kelas/delete/{id}', [SyllabusController::class, 'kelasDelete'])->name('kelas.delete');

    // CRUD Mapel
    Route::post('/syllabus/curiculum/{nama_kurikulum}/{kurikulum_id}/{fase_id}/{kelas_id}/mapel/store', [SyllabusController::class, 'mapelStore'])->name('mapel.store');
    Route::post('/syllabus/curiculum/mapel/update/{id}/{kelas_id}', [SyllabusController::class, 'mapelUpdate'])->name('mapel.update');
    Route::put('/syllabus/curiculum/mapel/activate/{id}', [SyllabusController::class, 'mapelActivate'])->name('mapel.activate');
    Route::delete('/syllabus/curiculum/mapel/delete/{id}', [SyllabusController::class, 'mapelDelete'])->name('mapel.delete');

    // CRUD Bab
    Route::post('/syllabus/curiculum/{nama_kurikulum}/{kurikulum_id}/{fase_id}/{kelas_id}/{mapel_id}/bab/store', [SyllabusController::class, 'babStore'])->name('bab.store');
    Route::post('/syllabus/curiculum/bab/update/{kurikulum_id}/{kelas_id}/{mapel_id}/{id}', [SyllabusController::class, 'babUpdate'])->name('bab.update');
    Route::put('/syllabus/curiculum/bab/activate/{id}', [SyllabusController::class, 'babActivate'])->name('bab.activate');
    Route::delete('/syllabus/curiculum/bab/delete/{id}', [SyllabusController::class, 'babDelete'])->name('bab.delete');

    // CRUD SUB BAB
    Route::post('/syllabus/curiculum/{nama_kurikulum}/{kurikulum_id}/{fase_id}/{kelas_id}/{mapel_id}/{bab_id}/sub-bab/store', [SyllabusController::class, 'subBabStore'])->name('subBab.store');
    Route::post('/syllabus/curiculum/sub-bab/update/{kurikulum_id}/{kelas_id}/{mapel_id}/{bab_id}/{id}', [SyllabusController::class, 'subBabUpdate'])->name('subBab.update');
    Route::put('/syllabus/curiculum/sub-bab/activate/{id}', [SyllabusController::class, 'subBabActivate'])->name('subBab.activate');
    Route::delete('/syllabus/curiculum/sub-bab/delete/{id}', [SyllabusController::class, 'subBabDelete'])->name('subBab.delete');

    // PAGINATE SYLLABUS-SERVICES
    Route::get('/paginate-syllabus-service-kurikulum', [FilterController::class, 'paginateSyllabusCuriculum'])->name('syllabus.kurikulum');
    Route::get('/paginate-syllabus-service-fase/{nama_kurikulum}/{id}', [FilterController::class, 'paginateSyllabusFase'])->name('syllabus.fase');
    Route::get('/paginate-syllabus-service-kelas/{nama_kurikulum}/{kurikulum_id}/{fase_id}', [FilterController::class, 'paginateSyllabusKelas'])->name('syllabus.kelas');
    Route::get('/paginate-syllabus-service-mapel/{nama_kurikulum}/{kurikulum_id}/{fase_id}/{kelas_id}', [FilterController::class, 'paginateSyllabusMapel'])->name('syllabus.mapel');
    Route::get('/paginate-syllabus-service-bab/{nama_kurikulum}/{kurikulum_id}/{fase_id}/{kelas_id}/{mapel_id}', [FilterController::class, 'paginateSyllabusBab'])->name('syllabus.bab');
    Route::get('/paginate-syllabus-service-sub-bab/{nama_kurikulum}/{kurikulum_id}/{fase_id}/{kelas_id}/{mapel_id}/{bab_id}', [FilterController::class, 'paginateSyllabusSubBab'])->name('syllabus.subBab');

    // BULKUPLOAD SYLLABUS
    Route::post('/syllabus/bulkupload/syllabus', [SyllabusController::class, 'bulkUploadSyllabus'])->name('syllabus.bulkupload');

    // ENGLISH ZONE ACCESS MIDDLEWARE
    Route::middleware([CheckEnglishZone::class])->group(function () {

    });
    Route::get('/english-zone', [EnglishZoneController::class, 'index'])->name('englishZone.index');

    // ROUTES ENGLISH ZONE
    // Routes View
    Route::get('upload-materi', [EnglishZoneController::class, 'uploadMateri'])->name('englisHone.uploadMateri');
    Route::get('/upload-soal', [EnglishZoneController::class, 'uploadSoal'])->name('englishZone.uploadSoal');
    // lalu route akan mendapatkan parameter yang dikirim oleh href tadi yang akan di proses oleh controller
    Route::get('/pengayaan/{modul}/{id}', [EnglishZoneController::class, 'pengayaan'])->name('pengayaan');
    Route::get('question-for-release', [EnglishZoneController::class, 'questionForRelease'])->name('englishZone.questionForRelease');

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

    Route::get('/getCertificate', [CertificateController::class, 'generateCertificate'])->name('generateCertificate');
    Route::post('/certificate', [EnglishZoneController::class, 'certificateStore'])->name('certificate.store');


    // ROUTES PKS (data sekolah & data murid pks)
    // VIEWS input data
    Route::get('/input-murid', [PksController::class, 'inputDataMurid'])->name('input-murid');
    // VIEWS management data
    Route::get('/data-sekolah', [PksController::class, 'managementDataSekolah'])->name('data-sekolah');
    Route::get('/data-sekolah-pks', [PksController::class, 'managementDaftarSekolah'])->name('data-sekolah-pks');
    Route::get('/data-civitas-sekolah/{sekolah}', [PksController::class, 'managementCivitasSekolah'])->name('data-civitas-sekolah');


    Route::get('/input-surat-pks', [PksController::class, 'inputSuratPks'])->name('input-surat-pks');
    Route::post('/data-surat-pks', [PksController::class, 'inputSuratPksStore'])->name('input-surat-pks.store');
    Route::post('/tambah-paket-pks', [PksController::class, 'tambahPaketPKS'])->name('tambahPaketPks.store');

    Route::get('/data-pks-sekolah', [PksController::class, 'dataPksSekolah'])->name('data-pks-sekolah');
    Route::get('/data-pks-sekolah/view/{sekolah}', [PksController::class, 'viewDataPksSekolah'])->name('data-pks-sekolah.edit');
    Route::get('/upload-bulk-upload', [PksController::class, 'bulkUploadCivitasSekolah'])->name('bulk-upload-civitas-sekolah');
    Route::post('/upload-bulk-upload-store', [PksController::class, 'bulkUploadCivitasSekolahStore'])->name('bulk-upload-civitas-sekolah.store');
    Route::put('/data-pks-sekolah/update/{id}', [PksController::class, 'updateDataPksSekolah'])->name('dataPksSekolah.update');

    //ROUTES IMPORT EXCEL
    Route::post('/import-data-murid', [ImportController::class, 'importDataMurid'])->name('import-data-murid');

    // ROUTES EXPORT TEMPLATE BULK UPLOAD EXCEL
    Route::get('/export-template-excel', [PKSController::class, 'generateTemplateExcel'])->name('BulkUpload-excel');

    // ROUTES LIST MENTOR
    // VIEWS
    Route::get('/mentor', [MitraCerdasController::class, 'mentorView'])->name('list.mentor');
    Route::get('/mentor-aktif', [MitraCerdasController::class, 'mentorAktifView'])->name('list.mentor.aktif');

    // CRUD list mentor
    Route::put('/active/mentor/{id}', [MitraCerdasController::class, 'listMentorUpdate'])->name('activeMentor.update');
    Route::put('/feature-active/mentor/{id}', [MitraCerdasController::class, 'mentorFeatureActive'])->name('activeFeatureMentor.update');
    // CRUD list mentor active

    // ROUTES TEMPLATE SIDEBAR
    Route::get('/sidebar', [WebController::class, 'sidebarBeranda']);
    Route::get('/sidebar-beranda-mobile', [WebController::class, 'sidebarBerandaMobile']);

    // ROUTES MASTERDATA(KERJASAMA SEKOLAH B2B & B2G)
    // VIEW
    Route::get('/upload-surat-pks', [SuratPKSController::class, 'index'])->name('suratPKS');
    // CRUD
    Route::post('/suratPKS', [suratPKSController::class, 'uploadSuratPKS'])->name('suratPKS.store');
    Route::post('/inputDataSekolah', [PksController::class, 'inputDataSekolahStore'])->name('inputDataSekolah.store');
    // SHOW PDF
    Route::get('/surat-pks-english-zone/{id}/{sekolah}', [SuratPKSController::class, 'generateSuratPKSEnglishZone'])->name('generateSuratPKSEnglishZone');

    // ROUTES VISITASIDATA (KERJASAMA SEKOLAH B2B & B2G) (Sales)
    // VIEW
    Route::get('/visitasi/jadwal-kunjungan', [VisitasiDataController::class, 'jadwalKunjungan'])->name('jadwalKunjungan');
    Route::get('/visitasi/data-kunjungan', [VisitasiDataController::class, 'dataKunjungan'])->name('dataKunjungan');
    Route::get('/visitasi/cetak-pks', [VisitasiDataController::class, 'cetakPKS'])->name('cetakPKS');
    Route::put('/status-cetak-pks/{id}', [PksController::class, 'updateStatusCetakPKS'])->name('statusCetakPKS.update');
    // CRUD
    Route::post('/visitasiData', [VisitasiDataController::class, 'visitasiDataStore'])->name('visitasiData.store');
    Route::put('/visitasiData/{id}', [VisitasiDataController::class, 'updateStatusKunjungan'])->name('visitasiData.update');
});