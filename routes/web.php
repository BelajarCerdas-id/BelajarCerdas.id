<?php

use App\Http\Middleware\TanyaHolidayAccess;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\MasterAcademicController;
use App\Http\Controllers\TanyaController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\EnglishZoneController;
use App\Http\Controllers\MitraCerdasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeatureManagementController;
use App\Http\Controllers\OfficeAccountController;
use App\Http\Controllers\PaymentFeaturesController;
use App\Http\Controllers\SchoolPartnerController;
use App\Http\Controllers\SoalPembahasanController;
use App\Http\Controllers\webController; // data biasa seperti foreach (tidak dari database) dan lain lain (jika ada selain foreach)
use App\Http\Middleware\FeaturePurchaseStudentOnly;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\TanyaMentorAccess;

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



    // ROUTES REGISTER
    Route::middleware([RedirectIfAuthenticated::class])->group(function () {
        // ROUTES VIEWS REGISTER STUDENT & MENTOR
        Route::get('/daftar-mentor', [AuthController::class, 'registerMentor'])->name('daftar.mentor');
        Route::get('/daftar', [AuthController::class, 'indexRegister'])->name('daftar.user');
        Route::get('/daftar-siswa', [AuthController::class, 'registerStudent'])->name('daftar.siswa');
    });

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
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

    // ROUTE LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // MIDDLEWARE ACCESS FEATURE PURCHASE VIEW
    Route::middleware([FeaturePurchaseStudentOnly::class])->group(function () {
        // ROUTE PAYMENT FEATURES VIEW
        Route::get('/pembayaran-fitur/{nama_fitur}', [PaymentFeaturesController::class, 'paymentFeaturesView'])->name('paymentFeaturesView');
    
        // ROUTE FEATURES STORE
        Route::get('/pembelian-fitur', [PaymentFeaturesController::class, 'featuresStore'])->name('featuresStore');
    });

    // ROUTES CHECKOUT FEATURES
    // Coin checkout tanya
    Route::post('/checkout-tanya', [PaymentFeaturesController::class, 'checkoutCoinTanya'])->name('checkout.tanya');

    // Checkout soal pembahasan subscription
    Route::post('/checkout-soal-pembahasan', [PaymentFeaturesController::class, 'checkoutSoalPembahasanSubcription'])->name('checkout.soal-pembahasan');

    // Checkout english zone subscription
    Route::post('/checkout-english-zone', [PaymentFeaturesController::class, 'checkoutEnglishZoneSubscription'])->name('checkout.english-zone');

    //ROUTE RENEW CHECKOUT PENDING
    Route::post('/renew-checkout/{id}', [PaymentFeaturesController::class, 'renewCheckoutPacketFeatures'])->name('checkout.pending');

    Route::post('/check-transaction-status/{id}', [PaymentFeaturesController::class, 'checkTransactionStatus'])->name('checkTransactionStatus');

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

    // (batch -> days -> hours in ez purchase dropdown)
    Route::get('/english-zone/purchase/dropdown-batches/{feature_variant_id}', [EnglishZoneController::class, 'dropdownBatchPurchase'])->name('batches.dropdown.purchase');
    Route::get('/english-zone/purchase/dropdown-days/{batch_id}', [EnglishZoneController::class, 'dropdownDaysPurchase'])->name('days.dropdown.purchase');
    Route::get('/english-zone/purchase/dropdown-hours/{batch_id}/{day}/{level_id}/{feature_variant_id}', [EnglishZoneController::class, 'dropdownHoursPurchase'])->name('hours.dropdown.purchase');

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

        // expire transaction
        Route::post('/expire-checkout-transaction', [filterController::class, 'expireTransaction'])->name('expireTransaction');

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

        // REFERRAL STUDENT TERDAFTAR (MENTOR)
        Route::get('/user-terdaftar/referral-code/{kode_referral}', [ProfileController::class, 'referralUserList'])->name('referralUserList.view');

        // PAGINATE REFERRAL USER LIST (list siswa yang terdaftar referral)
        Route::get('/paginate-user-terdaftar/referral-code/{kode_referral}', [FilterController::class, 'paginateReferralUserList'])->name('referralUserList.paginate');

        // STUDENT REFERRAL PURCHASE HISTORY (MENTOR)
        Route::get('/riwayat-paket-pembelian-siswa/referral-code/{kode_referral}', [ProfileController::class, 'studentReferralPurchaseHistory'])->name('studentReferralPurchaseHistory.view');

        // PAGINATE STUDENT REFERRAL PURCHASE HISTORY (MENTOR)
        Route::get('/paginate-riwayat-paket-pembelian-siswa/referral-code/{kode_referral}', [FilterController::class, 'paginateStudentReferralPurchaseHistory'])->name('studentReferralPurchaseHistory.paginate');

        // HISTORY PACKET ACTIVE
        Route::get('/riwayat-paket-aktif', [ProfileController::class, 'historyPacketActive'])->name('historyPacketActive.view');

        // UPDATE COIN USER WHEN QUESTION REJECTED (with pusher)
        Route::get('/update-koin-student', [TanyaController::class, 'getKoinStudent'])->name('tanya.getKoinStudent');

        // FILTER LEADERBOARD RANK TANYA STUDENT
        Route::get('/leaderboard-rank-tanya-student', [FilterController::class, 'leaderboardRankTanya'])->name('filterLeaderboardRankTanyaStudent');

        // ROUTES MANAGEMENTS (ADMINISTRATOR)
        // routes office accounts CRUD
        Route::get('/office-accounts-management', [OfficeAccountController::class, 'officeAccountsView'])->name('officeAccounts.view');
        Route::post('/office-accounts-management/store', [OfficeAccountController::class, 'officeAccountsStore'])->name('officeAccounts.store');

        // paginate list office accounts
        Route::get('/paginate-list-office-accounts', [OfficeAccountController::class, 'paginateListOfficeAccounts'])->name('listOfficeAccounts.paginate');

        // office account activate
        Route::put('/office-accounts-management/activate/{accountId}', [OfficeAccountController::class, 'officeAccountActivate'])->name('officeAccounts.activate');

        // routes features management (CRUD)
        Route::get('/features-management', [FeatureManagementController::class, 'featuresManagementView'])->name('featuresManagement.view');
        Route::post('/features-list-management/store', [FeatureManagementController::class, 'featuresManagementStore'])->name('featuresManagement.store');
        Route::post('/features-list-management/update/{featureId}', [FeatureManagementController::class, 'featuresManagementUpdate'])->name('featuresManagement.update');

        // paginate features list
        Route::get('/paginate-features-list', [FeatureManagementController::class, 'paginateFeaturesList'])->name('featuresManagement.paginate');

    // MENTOR FEATURE ACCESS MIDDLEWARE (untuk membatasi mentor yang tidak aktif pada fitur tanya)
    Route::middleware(['mentor.feature.access:1'])->group(function () {
        // TANYA HOLIDAY ACCESS MIDDLEWARE (untuk mencegah akses user pada fitur tanya di hari libur)
        Route::middleware([TanyaHolidayAccess::class])->group(function () {
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
    });

    Route::middleware(['mentor.feature.access:3'])->group(function () {

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

    // ROUTES FITUR SOAL DAN PEMBAHASAN
    // BANK SOAL VIEWS (ADMINISTRATOR)
    Route::get('/soal-pembahasan/bank-soal', [SoalPembahasanController::class, 'bankSoalView'])->name('SP.bankSoal.view');
    Route::get('/soal-pembahasan/bank-soal/{subBabId}/detail', [SoalPembahasanController::class, 'bankSoalDetail'])->name('SP.bankSoal.detail.view');
    Route::get('/soal-pembahasan/bank-soal/{subBabId}/{id}', [SoalPembahasanController::class, 'editQuestionView'])->name('SP.bankSoal.edit.question.view');
    Route::get('/soal-pembahasan/bank-soal/form/{subBabId}/{id}', [SoalPembahasanController::class, 'formEditQuestion'])->name('SP.bankSoal.form.edit.question');

    // CRUD BANK SOAL (ADMINISTRATOR)
    Route::post('/soal-pembahasan/bank-soal-store', [SoalPembahasanController::class, 'bankSoalStore'])->name('SP.bankSoal.store');
    Route::put('/soal-pembahasan/bank-soal/activate/{subBabId}', [SoalPembahasanController::class, 'bankSoalActivate'])->name('SP.bankSoal.activate');
    Route::post('/soal-pembahasan/bank-soal/update/{id}', [SoalPembahasanController::class, 'editQuestion'])->name('SP.bankSoal.edit.question.update');

    // PAGINATE BANK SOAL (ADMINISTRATOR)
    Route::get('/soal-pembahasan/paginate/bank-soal', [FilterController::class, 'paginateBankSoal'])->name('SP.bankSoal.paginate');
    Route::get('/soal-pembahasan/paginate/bank-soal/{subBab}/{subBabId}', [FilterController::class, 'paginateBankSoalDetail'])->name('SP.bankSoalDetail.paginate');

    // UPLOAD & DELETE IMAGE BANK SOAL WITH CKEDITOR
    Route::post('/bank-soal/edit-image', [SoalPembahasanController::class, 'editImageBankSoal'])->name('soalPembahasan.editImage');
    Route::post('/bank-soal/delete-image/endpoint', [SoalPembahasanController::class, 'deleteImageBankSoal'])->name('soalPembahasan.deleteImage');

    // SOAL PEMBAHASAN (STUDENT)
    Route::get('/soal-pembahasan/kelas', [SoalPembahasanController::class, 'soalPembahasanKelasView'])->name('soalPembahasanKelas.view');
    Route::get('/soal-pembahasan/kelas/{kelas}/{kelas_id}/mapel', [SoalPembahasanController::class, 'soalPembahasanMapelView'])->name('soalPembahasanMapel.view');
    Route::get('/soal-pembahasan/kelas/{kelas}/{kelas_id}/mapel/{mata_pelajaran}/{mapel_id}/bab', [SoalPembahasanController::class, 'soalPembahasanBabView'])->name('soalPembahasanBab.view');
    Route::get('/soal-pembahasan/kelas/{kelas}/{kelas_id}/mapel/{mata_pelajaran}/{mapel_id}/{bab_id}/sub-bab', [SoalPembahasanController::class, 'soalPembahasanSubBabView'])->name('soalPembahasanSubBab.view');
    Route::get('/soal-pembahasan/kelas/{kelas}/{kelas_id}/mapel/{mata_pelajaran}/{mapel_id}/{bab_id}/assessment', [SoalPembahasanController::class, 'soalPembahasanAssessmentView'])->name('soalPembahasanAssessment.view');

    // ASSESSMENT
    // PRACTICE VIEW
    Route::get('/soal-pembahasan/kelas/{kelas}/{kelas_id}/{mata_pelajaran}/{mapel_id}/{bab_id}/{sub_bab_id}/assessment/latihan', [SoalPembahasanController::class, 'practice'])->name('soalPembahasanAssessment.practice');

    // PRACTICE QUESTIONS FORM
    Route::get('/soal-pembahasan/kelas/{sub_bab_id}/assessment/latihan', [SoalPembahasanController::class, 'practiceQuestionsForm'])->name('practiceQuestions.form');

    // PRACTICE ANSWER
    Route::post('/soal-pembahasan/kelas/{sub_bab_id}/assessment/latihan/answer', [SoalPembahasanController::class, 'practiceAnswer'])->name('soalPembahasanAssessment.practice.answer');

    // EXAM VIEW
    Route::get('/soal-pembahasan/kelas/{kelas}/{kelas_id}/{mata_pelajaran}/{mapel_id}/{bab_id}/assessment/ujian', [SoalPembahasanController::class, 'exam'])->name('soalPembahasanAssessment.exam');

    // EXAM QUESTIONS FORM
    Route::get('/soal-pembahasan/kelas/{bab_id}/assessment/ujian', [SoalPembahasanController::class, 'examQuestionsForm'])->name('examQuestions.form');

    // EXAM ANSWER
    Route::post('/soal-pembahasan/kelas/{babId}/assessment/ujian/answer', [SoalPembahasanController::class, 'examAnswer'])->name('soalPembahasanAssessment.exam.answer');

    // PAGINATE RIWAYAT ASSESSMENT (PRACTICE AND EXAM STUDENT)
    Route::get('/soal-pembahasan/riwayat-assessment/paginate', [FilterController::class, 'paginateRiwayatAssessmentPracticeExam'])->name('riwayatAssessment.paginate');

    // HISTORY ASSESSMENT (PRACTICE AND EXAM)
    Route::get('/soal-pembahasan/riwayat-assessment/{materi_id}/{tipe_soal}/{date}/{kelas}/{mata_pelajaran}', [SoalPembahasanController::class, 'historyAssessmentView'])->name('historyAssessment.view');

    // HISTORY QUESTIONS ASSESSMENT (PRACTICE AND EXAM, untuk menampilkan soal yang sudah dijawab)
    Route::get('/soal-pembahasan/riwayat-assessment/{materi_id}/{tipe_soal}/{date}/{kelas}/{mata_pelajaran}/questions', [SoalPembahasanController::class, 'historyQuestionsAssessment'])->name('historyQuestionSoalPembahasanAssessment');

    // ROUTES FITUR ENGLISH ZONE
    // MANAGEMENT LEVELS
    // views(ADMINISTRATOR)
    Route::get('/english-zone/management-levels', [EnglishZoneController::class, 'managementLevelView'])->name('EZ.managementLevel.view');

    // CRUD (ADMINISTRATOR)
    // management level
    Route::post('/english-zone/management-levels/store', [EnglishZoneController::class, 'managementLevelStore'])->name('EZ.managementLevel.store');
    Route::put('/english-zone/management-levels/edit/{id}', [EnglishZoneController::class, 'managementLevelEdit'])->name('EZ.managementLevel.edit');
    Route::delete('/english-zone/management-levels/delete/{id}', [EnglishZoneController::class, 'managementLevelDelete'])->name('EZ.managementLevel.delete');

    // PAGINATE
    Route::get('/english-zone/management-levels/paginate', [EnglishZoneController::class, 'paginateManagementLevel'])->name('EZ.managementLevel.paginate');

    // MANAGEMENT SESSION (ADMINISTRATOR)
    // views
    Route::get('/english-zone/management-levels/{levelId}/management-session', [EnglishZoneController::class, 'managementSessionView'])->name('EZ.managementSession.view');

    // crud
    Route::post('/english-zone/management-levels/management-session/store', [EnglishZoneController::class, 'managementSessionStore'])->name('EZ.managementSession.store');
    Route::post('/english-zone/management-levels/management-session/edit/{id}', [EnglishZoneController::class, 'managementSessionEdit'])->name('EZ.managementSession.edit');
    Route::delete('/english-zone/management-levels/management-session/delete/{id}', [EnglishZoneController::class, 'managementSessionDelete'])->name('EZ.managementSession.delete');

    // paginate
    Route::get('/english-zone/management-levels/{levelId}/management-session/paginate', [EnglishZoneController::class, 'paginateManagementSession'])->name('EZ.managementSession.paginate');

    // dropdown bertingkat level -> session
    Route::get('/english-zone/session-dropdown/{levelId}', [EnglishZoneController::class, 'dropdownSessionByLevel'])->name('EZ.session.dropdown');
    
    // BANK SOAL
    // views(ADMINISTRATOR)
    Route::get('/english-zone/bank-soal', [EnglishZoneController::class, 'bankSoalView'])->name('EZ.bankSoal.view');
    Route::get('/english-zone/bank-soal/{levelId}/{sessionId}/detail', [EnglishZoneController::class, 'bankSoalDetail'])->name('EZ.bankSoal.detail.view');
    Route::get('/english-zone/bank-soal/{levelId}/{sessionId}/{id}', [EnglishZoneController::class, 'editQuestionView'])->name('EZ.bankSoal.edit.question.view');
    Route::get('/english-zone/bank-soal/form/{levelId}/{sessionId}/{id}', [EnglishZoneController::class, 'formEditQuestion'])->name('EZ.bankSoal.form.edit.question');

    // CRUD (ADMINISTRATOR)
    Route::post('/english-zone/bank-soal-store', [EnglishZoneController::class, 'bankSoalStore'])->name('EZ.bankSoal.store');
    Route::put('/english-zone/bank-soal/activate/{levelId}/{sessionId}', [EnglishZoneController::class, 'bankSoalActivate'])->name('EZ.bankSoal.activate');
    Route::post('/english-zone/bank-soal/update/{id}', [EnglishZoneController::class, 'editQuestion'])->name('EZ.bankSoal.edit.question.update');

    // PAGINATE (ADMINISTRATOR)
    Route::get('/english-zone/paginate/bank-soal', [EnglishZoneController::class, 'paginateBankSoal'])->name('EZ.bankSoal.paginate');
    Route::get('/english-zone/paginate/bank-soal/{levelId}/{sessionId}', [EnglishZoneController::class, 'paginateBankSoalDetail'])->name('EZ.bankSoalDetail.paginate');

    // UPLOAD & DELETE IMAGE WITH CKEDITOR
    Route::post('/english-zone/bank-soal/edit-image', [EnglishZoneController::class, 'editImageBankSoal'])->name('englishZone.editImage');
    Route::post('/english-zone/bank-soal/delete-image/endpoint', [EnglishZoneController::class, 'deleteImageBankSoal'])->name('englishZone.deleteImage');

    // MANAGEMENT QUIZ
    // passage
    // views
    Route::get('/english-zone/management-quiz/management-passage', [EnglishZoneController::class, 'managementPassageView'])->name('EZ.managementPassage.view');
    Route::get('/english-zone/management-quiz/management-passage/{level_id}/{passage_type}/detail', [EnglishZoneController::class, 'managementPassageDetail'])->name('EZ.managementPassageDetail.view');

    // CRUD
    Route::post('/english-zone/management-quiz/management-passage/store', [EnglishZoneController::class, 'managementPassageStore'])->name('EZ.managementPassage.store');
    Route::post('/english-zone/management-quiz/management-passage/{id}/edit', [EnglishZoneController::class, 'managementPassageEdit'])->name('EZ.managementPassage.edit');
    Route::delete('/english-zone/management-quiz/management-passage/{id}/delete', [EnglishZoneController::class, 'managementPassageDelete'])->name('EZ.managementPassage.delete');
    Route::put('/english-zone/management-quiz/management-passage/{id}/activate', [EnglishZoneController::class, 'managementPassageActivate'])->name('EZ.managementPassage.activate');

    // PAGINATE
    Route::get('/english-zone/management-quiz/management-passage/paginate', [EnglishZoneController::class, 'paginateManagementPassage'])->name('EZ.managementPassage.paginate');
    Route::get('/english-zone/management-quiz/management-passage-detail/{level_id}/{passage_type}/paginate', [EnglishZoneController::class, 'paginateManagementPassageDetail'])->name('EZ.managementPassageDetail.paginate');

    // bank soal quiz
    // views
    Route::get('/english-zone/management-quiz/management-passage/{level_id}/{passage_id}/{passage_type}/bank-soal', [EnglishZoneController::class, 'managementBankSoalQuizView'])->name('EZ.managementBankSoalQuiz.view');
    Route::get('/english-zone/management-quiz/management-passage/{level_id}/{passage_id}/{passage_type}/{question_id}/bank-soal/edit', [EnglishZoneController::class, 'editQuestionQuizView'])->name('EZ.bankSoalQuiz.edit.view');
    Route::get('/english-zone/management-quiz/management-passage/{level_id}/{passage_id}/{passage_type}/{question_id}/bank-soal/edit/form', [EnglishZoneController::class, 'editQuestionQuizForm'])->name('EZ.bankSoalQuiz.edit.form');

    // CRUD
    Route::post('/english-zone/management-quiz/management-passage/{level_id}/{passage_id}/{passage_type}/bank-soal/store', [EnglishZoneController::class, 'bankSoalQuizStore'])->name('EZ.bankSoalQuiz.store');
    Route::post('/english-zone/management-quiz/management-passage/{level_id}/{passage_id}/{passage_type}/{question_id}/bank-soal/edit/submit', [EnglishZoneController::class, 'editQuestionQuizUpdate'])->name('EZ.bankSoalQuiz.edit.submit');
    Route::delete('/english-zone/management-quiz/management-passage/{question_id}/bank-soal/delete', [EnglishZoneController::class, 'bankSoalQuizDelete'])->name('EZ.bankSoalQuiz.delete');
    Route::put('/english-zone/management-quiz/management-passage/{question_id}/bank-soal/activate', [EnglishZoneController::class, 'bankSoalQuizActivate'])->name('EZ.bankSoalQuiz.activate');

    // PAGINATE
    Route::get('/english-zone/management-quiz/management-passage/{level_id}/{passage_id}/{passage_type}/bank-soal/paginate', [EnglishZoneController::class, 'paginateManagementBankSoalQuiz'])->name('EZ.managementBankSoalQuiz.paginate');

    // MANAGEMENT MATERI
    // views (ADMINISTRATOR)
    Route::get('/english-zone/management-materi', [EnglishZoneController::class, 'managementMateriView'])->name('EZ.managementMateri.view');
    Route::get('/english-zone/management-materi/detail/{id}', [EnglishZoneController::class, 'managementMateriDetail'])->name('EZ.managementDetail.view');

    // CRUD
    Route::post('/english-zone/management-materi/store', [EnglishZoneController::class, 'managementMateriStore'])->name('EZ.managementMateri.store');
    Route::post('/english-zone/management-materi/edit/{id}', [EnglishZoneController::class, 'managementMateriEdit'])->name('EZ.managementMateri.edit');
    Route::delete('/english-zone/management-materi/delete/{id}', [EnglishZoneController::class, 'managementMateriDelete'])->name('EZ.managementMateri.delete');
    
    // PAGINATE
    Route::get('/english-zone/management-materi/paginate', [EnglishZoneController::class, 'paginateManagementMateri'])->name('EZ.managementMateri.paginate');
    Route::get('/english-zone/management-materi/detail/paginate/{id}', [EnglishZoneController::class, 'paginateManagementMateriDetail'])->name('EZ.managementMateriDetail.paginate');

    // MANAGEMENT ZOOM
    // views (ADMINISTRATOR)
    Route::get('/english-zone/management-zoom', [EnglishZoneController::class, 'managementZoomView'])->name('EZ.managementZoom.view');

    // CRUD
    Route::post('/english-zone/management-zoom/store', [EnglishZoneController::class, 'managementZoomStore'])->name('EZ.managementZoom.store');
    Route::put('/english-zone/management-zoom/edit/{id}', [EnglishZoneController::class, 'managementZoomEdit'])->name('EZ.managementZoom.edit');
    Route::delete('/english-zone/management-zoom/delete/{id}', [EnglishZoneController::class, 'managementZoomDelete'])->name('EZ.managementZoom.delete');

    // PAGINATE
    Route::get('/english-zone/management-zoom/paginate', [EnglishZoneController::class, 'paginateManagementZoom'])->name('EZ.managementZoom.paginate');
    
    // MANAGEMENT BATCHES
    // views (ADMINISTRATOR)
    Route::get('/english-zone/management-batches', [EnglishZoneController::class, 'managementBatchesView'])->name('EZ.managementBatches.view');
    
    // CRUD
    Route::post('/english-zone/management-batches/store', [EnglishZoneController::class, 'managementBatchesStore'])->name('EZ.managementBatches.store');
    Route::put('/english-zone/management-batches/{id}', [EnglishZoneController::class, 'managementBatchEdit'])->name('EZ.managementBatch.edit');
    
    // PAGINATE
    Route::get('/english-zone/management-batches/paginate', [EnglishZoneController::class, 'paginateManagementBatches'])->name('EZ.managementBatches.paginate');

    // DROPDOWN mentors in administrator
    Route::get('/schedule-dropdown-mentors', [EnglishZoneController::class, 'dropdownMentors'])->name('mentors.dropdown');
    
    // MANAGEMENT BATCHES SCHEDULE
    // views (ADMINISTRATOR)
    Route::get('/english-zone/management-batches/schedule/{batch_name}/{batch_id}', [EnglishZoneController::class, 'managementBatchScheduleView'])->name('EZ.managementBatchSchedule.view');

    // CRUD
    Route::post('/english-zone/management-batches/schedule/store/{batch_name}/{batch_id}', [EnglishZoneController::class, 'managementBatchScheduleStore'])->name('EZ.managementBatchSchedule.store');
    Route::put('/english-zone/management-batches/schedule/edit/{batch_id}/{batch_schedule_id}', [EnglishZoneController::class, 'managementBatchScheduleEdit'])->name('EZ.managementBatchSchedule.edit');
    Route::delete('/english-zone/management-batches/schedule/delete/{batch_schedule_id}', [EnglishZoneController::class, 'managementBatchScheduleDelete'])->name('EZ.managementBatchSchedule.delete');

    // PAGINATE
    Route::get('/english-zone/management-batches/schedule/paginate/{batch_name}/{batch_id}', [EnglishZoneController::class, 'paginateManagementBatchSchedule'])->name('EZ.managementBatchSchedule.paginate');

    // MANAGEMENT MENTOR SCHEDULE
    // views (ADMINISTRATOR)
    Route::get('/english-zone/management-mentor/schedule', [EnglishZoneController::class, 'managementMentorScheduleView'])->name('EZ.managementMentorSchedule.view');

    // PAGINATE
    Route::get('/english-zone/management-mentor/schedule/paginate', [EnglishZoneController::class, 'paginateManagementMentorSchedule'])->name('EZ.managementMentorSchedule.paginate');

    // ACTIVATE
    Route::post('/english-zone/management-mentor/schedule/activate', [EnglishZoneController::class, 'managementMentorScheduleActivate'])->name('EZ.managementMentorSchedule.activate');

    // MANAGEMENT STUDENT BATCH (ADMINISTRATOR)
    // views
    Route::get('/english-zone/management-student-batch', [EnglishZoneController::class, 'managementStudentBatchView'])->name('EZ.managementStudentBatch.view');
    // Non-school partner (tanpa schoolId)
    Route::get('/english-zone/management-student-batch/detail/non-school-partner/{featureVariantId}/{levelId}/{batchId}/{batchScheduleGroups}/{batchScheduleIds}/{studentIds}', [EnglishZoneController::class, 'studentBatchDetailView'])->name('EZ.managementStudentBatchDetail.view.nonSchool');
    // School partner (dengan schoolId)
    Route::get('/english-zone/management-student-batch/detail/school-partner/{featureVariantId}/{levelId}/{batchId}/{batchScheduleGroups}/{batchScheduleIds}/{studentIds}/{schoolPartnerId}', [EnglishZoneController::class, 'studentBatchDetailView'])->name('EZ.managementStudentBatchDetail.view.school');
    
    // paginate
    Route::get('/english-zone/management-student-batch/non-school-partner/paginate', [EnglishZoneController::class, 'paginateStudentBatchNonSchoolPartner'])->name('EZ.tudentBatchNonSchoolPartner.paginate');
    Route::get('/english-zone/management-student-batch/school-partner/paginate', [EnglishZoneController::class, 'paginateStudentBatchSchoolPartner'])->name('EZ.studentBatchSchoolPartner.paginate');
    // Non-school partner (tanpa schoolId)
    Route::get('/english-zone/management-student-batch-detail/non-school-partner/{featureVariantId}/{levelId}/{batchId}/{batchScheduleGroups}/{batchScheduleIds}/{studentIds}/paginate', [EnglishZoneController::class, 'paginateManagementStudentBatchDetail'])->name('EZ.managementStudentBatchDetail.paginate.nonSchool');
    // School partner (dengan schoolId)
    Route::get('/english-zone/management-student-batch-detail/school-partner/{featureVariantId}/{levelId}/{batchId}/{batchScheduleGroups}/{batchScheduleIds}/{studentIds}/{schoolPartnerId}/paginate', [EnglishZoneController::class, 'paginateManagementStudentBatchDetail'])->name('EZ.managementStudentBatchDetail.paginate.school');

    // activate menntor
    Route::put('/english-zone/management-student-batch/activate-mentor/{batch_schedule_ids}', [EnglishZoneController::class, 'studentBatchActivateMentor'])->name('EZ.studentBatchMentor.activate');

    // Re-Schedule students batch
    Route::post('/english-zone/management-student-batch-detail/reschedule', [EnglishZoneController::class, 'studentBatchDetailReSchedule'])->name('EZ.studentBatchDetailReSchedule.detail');

    // Refund student batch
    Route::post('/english-zone/management-student-batch-detail/{studentId}/{transactionSource}/refund', [EnglishZoneController::class, 'studentBatchDetailRefund'])->name('EZ.studentBatchDetailRefund.detail');

    // dropdown bertingkat in student batch detail
    Route::get('/english-zone/management-student-batch-detail/dropdown-days/{batch_id}', [EnglishZoneController::class, 'dropdownDayStudentBatch'])->name('EZ.dropdownDayStudentBatch');
    Route::get('/english-zone/management-student-batch-detail/school-partner/dropdown-hours/{batch_id}/{group_id}/{level_id}/{feature_variant_id}/{transaction_source}/{school_id}', [EnglishZoneController::class, 'dropdownHourStudentBatch'])->name('EZ.dropdownHourStudentBatch.nonSchool');
    Route::get('/english-zone/management-student-batch-detail/non-school-partner/dropdown-hours/{batch_id}/{group_id}/{level_id}/{transaction_source}/{feature_variant_id}', [EnglishZoneController::class, 'dropdownHourStudentBatch'])->name('EZ.dropdownHourStudentBatch.school');

    // MENTOR SIDE
    // mentor feature access middleware (untuk membatasi mentor yang tidak aktif pada fitur english zone)
    Route::middleware(['mentor.feature.access:3'])->group(function () {
        // views
        Route::get('/english-zone-mentor', [EnglishZoneController::class, 'englishZoneMentorView'])->name('EZ.mentor.view');
        Route::get('/english-zone-mentor/student-batch/detail/{featureVariantId}/{levelId}/{batchId}/{batchScheduleGroups}/{batchScheduleIds}/{studentIds}', [EnglishZoneController::class, 'mentorStudentBatchDetailView'])->name('EZ.mentorStudentBatchDetail.view');
    
        // paginate
        Route::get('/english-zone-mentor/student-batch/non-school-partner/paginate', [EnglishZoneController::class, 'paginateMentorStudentBatchNonSchoolPartner'])->name('EZ.mentorStudentBatch.paginate.nonSchool');
        Route::get('/english-zone-mentor/student-batch/school-partner/paginate', [EnglishZoneController::class, 'paginateMentorStudentBatchSchoolPartner'])->name('EZ.mentorStudentBatch.paginate.school');
        Route::get('/english-zone-mentor/student-batch-detail/{featureVariantId}/{levelId}/{batchId}/{batchScheduleGroups}/{batchScheduleIds}/{studentIds}/paginate', [EnglishZoneController::class, 'paginateMentorStudentBatchDetail'])->name('EZ.mentorStudentBatchDetail.paginate');
        Route::get('/english-zone-mentor/student-batch-detail/materi/{levelId}/{studentIds}/{activeLevel}/paginate', [EnglishZoneController::class, 'paginateMentorStudentBatchDetailMateri'])->name('EZ.mentorStudentBatchDetailMateri.paginate');
    });

    // STUDENT SIDE
    // views
    Route::get('/english-zone', [EnglishZoneController::class, 'englishZoneStudentView'])->name('EZ.student.view');
    Route::get('/english-zone/{levelId}/worksheet-detail', [EnglishZoneController::class, 'worksheetDetailView'])->name('EZ.student.worksheet.detail.view');
    Route::get('/english-zone/{levelId}/{sessionId}/exam', [EnglishZoneController::class, 'examView'])->name('EZ.exam.view');

    // crud
    Route::post('/english-zone-student/attendance', [EnglishZoneController::class, 'submitStudentAttendance'])->name('EZ.submitStudentAttendance.store');

    // paginate
    Route::get('/english-zone-student/materi/{levelIds}/{activeLevel}/paginate', [EnglishZoneController::class, 'paginateStudentMateri'])->name('EZ.studentMateri.paginate');
    Route::get('/english-zone/{levelId}/worksheet-detail/paginate', [EnglishZoneController::class, 'paginateWorksheetDetail'])->name('EZ.worksheet.detail.paginate');
    Route::get('/english-zone-student/attendance/paginate', [EnglishZoneController::class, 'paginateStudentAttendanceHistory'])->name('EZ.studentAttendanceHistory.paginate');

    // assessment
    // exam TOEP form
    Route::get('/english-zone-student/{levelId}/{sessionId}/exam-TOEP/form', [EnglishZoneController::class, 'questionFormExamTOEP'])->name('EZ.examTOEP.assessment');

    // exam TOEP answers
    Route::post('/english-zone-student/{levelId}/{sessionId}/exam-TOEP/answers', [EnglishZoneController::class, 'examTOEPAnswers'])->name('EZ.examTOEP.answers');

    // QUIZ EXAM (Reading, Writing, Listening, Speaking)
    // views
    Route::get('/english-zone/{levelId}/quiz-detail', [EnglishZoneController::class, 'quizDetailView'])->name('EZ.quizDetail.view');
    Route::get('/english-zone/{levelId}/quiz/reading-practice-test', [EnglishZoneController::class, 'quizReadingPracticeTest'])->name('EZ.quizReadingPracticeTest.view');

    // quiz reading answers
    Route::post('/english-zone-student/{levelId}/{passageId}/quiz/reading-practice-test/answers', [EnglishZoneController::class, 'quizReadingPracticeTestAnswer'])->name('EZ.quizReadingPracticeTest.answers');

    // fetch
    Route::get('/english-zone/{levelId}/quiz-detail/fetch', [EnglishZoneController::class, 'quizDetailFetch'])->name('EZ.quizDetail.fetch');
    Route::get('/english-zone/{levelId}/quiz/reading-practice-test/form', [EnglishZoneController::class, 'quizReadingPracticeTestForm'])->name('EZ.quizReadingPracticeTest.form');

    // ROUTES SCHOOL PARTNER
    // school subscription
    Route::get('/school-subscription', [SchoolPartnerController::class, 'schoolSubscriptionView'])->name('schoolSubscription.view');
    Route::post('/school-subcsription/store', [SchoolPartnerController::class, 'bulkUploadSchoolPartner'])->name('bulkUploadSchoolPartner.store');

    // user school subscription
    Route::get('/school-subscription/{schoolId}/user', [SchoolPartnerController::class, 'userSchoolSubscriptionView'])->name('userSchoolSubscription.view');

    // activate by student
    Route::put('/school-subscription/activate/{id}/user', [SchoolPartnerController::class, 'activateFeatureByStudent'])->name('activateFeatureByStudent');

    // activate all student by school
    Route::put('/school-subscription/activate/{schoolId}/{featureId}', [SchoolPartnerController::class, 'activateFeatureForAllStudents'])->name('activateFeatureForAllStudents');

    // paginate list school partner
    Route::get('/list-school-partner/paginate', [SchoolPartnerController::class, 'paginateListSchoolPartner'])->name('listSchoolPartner.paginate');

    // paginate list user school subscription
    Route::get('/list-user-school-subscription/paginate/{schoolId}', [SchoolPartnerController::class, 'paginateListUserSchoolSubscription'])->name('listUserSchoolSubscription.paginate');

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
});