<?php

use App\Models\User;
use App\Models\Mahasiswa;
use App\Modules\UserManagement\Controllers\MahasiswaController;
use App\Modules\UserManagement\Controllers\UserManagementController;
use App\Modules\UserManagement\Controllers\DosenController;
use App\Modules\UserManagement\Controllers\ForgotPasswordController;
use App\Modules\UserManagement\Controllers\PengajuanPisahKoTAController;
use App\Modules\UserManagement\Controllers\FormPisahKoTAController;
use App\Modules\UserManagement\Controllers\KBKController;
use App\Modules\UserManagement\Controllers\ProgramStudiController;
use App\Modules\UserManagement\Controllers\DashboardController;
use App\Modules\UserManagement\Controllers\PerekrutanAnggotaKoTAController;
use App\Modules\UserManagement\Controllers\KonfirmasiKoTAController;
use App\Modules\UserManagement\Controllers\DetailKoTAController;
use App\Modules\UserManagement\Controllers\ProfileController;
use App\Modules\UserManagement\Controllers\ManagementKoTAController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Modules\UserManagement\Controllers\LogAktivitasController;


use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Modules\UserManagement\Controllers\TokenVerify;
use App\Modules\UserManagement\Controllers\ImpersonateController;

use FontLib\Table\Type\name;

$prefix = env('PREFIX_URL', 'sipta-dev');

Route::prefix($prefix)->group(function () {

    /**
     * ========== Auth Routes ==========
     */
    Route::middleware('guest')->group(function () {
        Route::get('/login', fn () => view('UserManagement.views.auth.login'))->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

        Route::get('/register', fn () => view('UserManagement.views.auth.register'))->name('register');

        Route::get('/forgot-password', fn () => view('UserManagement.views.auth.forgot-password'))->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

        Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
    });

    Route::middleware(['auth', 'redirect.after.logout'])->group(function(){
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });


    /**
     * ========== User Management ==========
     */
    Route::get('/user_management', [UserManagementController::class, 'render']);

    Route::middleware(['auth', 'can:admin'])->group(function () {
        Route::get('/manajemen-akun-dosen', [UserManagementController::class, 'manage_dosen'])->name('manage.dosen');
        Route::get('/manajemen-akun-mahasiswa', [UserManagementController::class, 'manage_mhs'])->name('manage.mhs');

        // Dosen
        Route::post('/add_new_dosen', [DosenController::class, 'add_new_dosen'])->name('dosen.add_new_dosen');
        Route::post('/update-dosen', [DosenController::class, 'updateDosen'])->name('dosen.updateDosen');
        Route::post('/delete-dosen', [DosenController::class, 'deleteDosen'])->name('dosen.deleteDosen');
        Route::post('/nonaktif-dosen', [DosenController::class, 'nonaktifkanAkun'])->name('nonaktif-dosen');
        Route::post('/aktif-dosen', [DosenController::class, 'aktifkanAkun'])->name('aktif-dosen');
        Route::post('/update_role', [DosenController::class, 'update_role'])->name('dosen.update_role');
        Route::post('/import-dosen', [DosenController::class, 'import'])->name('import-dosen');
        Route::get('/previewDataDosen', [DosenController::class, 'previewDataDosen'])->name('previewDataDosen');
        Route::post('/inputBulkDosen', [DosenController::class, 'inputBulk'])->name('inputBulkDosen');
        Route::post('/updateBulkRole', [DosenController::class, 'updateBulkRole'])->name('updateBulkRole');

        // Mahasiswa
        Route::post('/addNewMhs', [MahasiswaController::class, 'addNewMhs'])->name('mahasiswa.addNewMhs');
        Route::post('/updateMhs', [MahasiswaController::class, 'updateMhs'])->name('mahasiswa.updateMhs');
        Route::post('/import-mhs', [MahasiswaController::class, 'import'])->name('import-mhs');
        Route::get('/previewDataMhs', [MahasiswaController::class, 'previewDataMhs'])->name('previewDataMhs');
        Route::post('/inputBulkMhs', [MahasiswaController::class, 'inputBulk'])->name('inputBulkMhs');
        Route::post('/nonaktif-mhs', [MahasiswaController::class, 'nonaktifkanAkun'])->name('nonaktif-mhs');
        Route::post('/aktif-mhs', [MahasiswaController::class, 'aktifkanAkun'])->name('aktif-mhs');

        // Download template
        Route::get('/download-template-dosen', fn () => Response::download(public_path('UserManagement/template-registrasi-dosen.xlsx'), 'template-registrasi-dosen.xlsx'))->name('download.template-dosen');
        Route::get('/download-template-mahasiswa', fn () => Response::download(public_path('UserManagement/template-registrasi-mahasiswa.xlsx'), 'template-registrasi-mahasiswa.xlsx'))->name('download.template-mhs');

        // KBK
        Route::get('/kelola-kbk', [KBKController::class, 'index'])->name('kelola-kbk');
        Route::post('/kelola-kbk', [KBKController::class, 'store'])->name('kelola-kbk.store');
        Route::post('/kelola-kbk/update/{id}', [KBKController::class, 'update'])->name('kelola-kbk.update');
        Route::delete('/kelola-kbk/{id}', [KBKController::class, 'destroy'])->name('kelola-kbk.destroy');
        Route::put('/kelola-kbk/update/{id}', [KBKController::class, 'update'])->name('kelola-kbk.update');


        // Program Studi
        Route::get('/program-studi', [ProgramStudiController::class, 'index'])->name('program-studi.index');
        Route::get('/program-studi/create', [ProgramStudiController::class, 'create'])->name('program-studi.create');
        Route::post('/program-studi', [ProgramStudiController::class, 'store'])->name('program-studi.store');
        Route::get('/program-studi/{id}/edit', [ProgramStudiController::class, 'edit'])->name('program-studi.edit');
        Route::put('/program-studi/{id}', [ProgramStudiController::class, 'update'])->name('program-studi.update');
        Route::delete('/program-studi/{id}', [ProgramStudiController::class, 'destroy'])->name('program-studi.destroy');

        // Log Aktivitas
        Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');
    });


    /**
     * ========== Mahasiswa & Koordinator TA ==========
     */
    Route::middleware(['auth', 'can:mahasiswa_ta'])->group(function () {
        Route::get('/form-pisah-kota', [FormPisahKoTAController::class, 'showFormPisah'])->name('form.pisah.kota');
        Route::post('/form-pisah-kota/ajukan', [FormPisahKoTAController::class, 'ajukan'])->name('form.pisah.kota.ajukan');
        Route::post('/form-pisah-kota/prakota', [FormPisahKoTAController::class, 'prakota'])->name('form.pisah.kota.prakota');
        Route::post('/form-pisah-kota/batal', [FormPisahKoTAController::class, 'batal'])->name('form.pisah.kota.batal');

        Route::get('/tambah-anggota-kota/{id}', [PerekrutanAnggotaKoTAController::class, 'showTambahAnggotaForm'])->name('tambah-anggota-kota');
        Route::post('/tambah-anggota-kota/{id}', [PerekrutanAnggotaKoTAController::class, 'tambahAnggota'])->name('tambah-anggota-kota.submit');
    });

    Route::middleware(['auth', 'can:koordinator_ta'])->group(function () {
        Route::get('/pengajuan-pisah-kota', [PengajuanPisahKoTAController::class, 'index'])->name('pengajuan.pisah.kota');
        Route::get('/pengajuan-pisah-kota/{id}', [PengajuanPisahKoTAController::class, 'show'])->name('pengajuan.pisah.kota.show');
        Route::patch('/pengajuan-pisah-kota/{id}/terima', [PengajuanPisahKoTAController::class, 'terima'])->name('pengajuan.pisah.kota.terima');
        Route::patch('/pengajuan-pisah-kota/{id}/tolak', [PengajuanPisahKoTAController::class, 'tolak'])->name('pengajuan.pisah.kota.tolak');

        Route::get('/management-kota', [ManagementKoTAController::class, 'index'])->name('management-kota');
        Route::get('/detail-kota/{id}', [DetailKoTAController::class, 'index'])->name('detail.kota');
    });


    /**
     * ========== Mahasiswa (All) ==========
     */
    Route::middleware(['auth', 'can:all_mahasiswa'])->group(function () {
        Route::get('/perekrutan-anggota-kota', [PerekrutanAnggotaKoTAController::class, 'index'])->name('perekrutan-anggota-kota');
        Route::post('/perekrutan-anggota-kota', [PerekrutanAnggotaKoTAController::class, 'submit'])->name('perekrutan-anggota-kota.submit');

        Route::get('/konfirmasi-kota', [KonfirmasiKoTAController::class, 'index'])->name('konfirmasi-kota');
        Route::get('/kota-saya', [DetailKoTAController::class, 'index'])->name('kota.saya');
    });


    /**
     * ========== Profile & Dashboard ==========
     */
    Route::middleware(['auth'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/impersonate/{id}', [ImpersonateController::class, 'impersonate'])->name('impersonate');
        Route::get('/impersonate-leave', [ImpersonateController::class, 'leave'])->name('impersonate.leave');
    });

    Route::get('/data-mahasiswa', [UserManagementController::class, 'show_mhs']);
    Route::get('/test-dashboard', [DashboardController::class, 'showPengajuanSeminar3']);
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * ========== Misc ==========
     */
    Route::get('/usermanagement/v1/role', [TokenVerify::class, 'verifyToken']);
});

Route::middleware(['auth', 'can:koordinator_ta'])->group(function () {
    Route::get('/pengajuan-pisah-kota', [PengajuanPisahKoTAController::class, 'index'])
    ->name('pengajuan.pisah.kota');
    
    Route::get('/pengajuan-pisah-kota/{id}', [PengajuanPisahKoTAController::class, 'show'])
    ->name('pengajuan.pisah.kota.show');
});


Route::get('/form-pisah-kota', [FormPisahKoTAController::class, 'showFormPisah'])
        ->name('form.pisah.kota')
        ->middleware('can:mahasiswa_ta');

Route::post('/form-pisah-kota/ajukan', [FormPisahKoTAController::class, 'ajukan'])->name('form.pisah.kota.ajukan');
Route::post('/form-pisah-kota/prakota', [FormPisahKoTAController::class, 'prakota'])->name('form.pisah.kota.prakota');
Route::post('/form-pisah-kota/batal', [FormPisahKotaController::class, 'batal'])->name('form.pisah.kota.batal');
Route::patch('/pengajuan-pisah-kota/{id}/terima', [PengajuanPisahKoTAController::class, 'terima'])
        ->name('pengajuan.pisah.kota.terima')
        ->middleware('can:koordinator_ta');
Route::patch('/pengajuan-pisah-kota/{id}/tolak', [PengajuanPisahKoTAController::class, 'tolak'])
        ->name('pengajuan.pisah.kota.tolak')
        ->middleware('can:koordinator_ta');


Route::get('/manajemen-akun-dosen', [UserManagementController::class, 'manage_dosen'])
    ->middleware('auth', 'can:admin')
    ->name('manage.dosen');
    
Route::post('/delete-dosen', [DosenController::class, 'deleteDosen'])->name('dosen.deleteDosen');
Route::post('/update-dosen', [DosenController::class, 'updateDosen'])->name('dosen.updateDosen');

Route::post('/update_role', [DosenController::class, 'update_role'])->name('dosen.update_role');
Route::post('/add_new_dosen', [DosenController::class, 'add_new_dosen'])->name('dosen.add_new_dosen');

Route::get('/manajemen-akun-mahasiswa', [UserManagementController::class, 'manage_mhs'])
        ->middleware('auth', 'can:admin')
        ->name('manage.mhs');

Route::post('/addNewMhs', [MahasiswaController::class, 'addNewMhs'])->name('mahasiswa.addNewMhs');
Route::post('/updateMhs', [MahasiswaController::class, 'updateMhs'])->name('mahasiswa.updateMhs');
Route::get('/previewDataMhs', [MahasiswaController::class, 'previewDataMhs'])->name('previewDataMhs');
Route::post('/inputBulkMhs', [MahasiswaController::class, 'inputBulk'])->name('inputBulkMhs');

Route::get('/download-template-dosen', function () {
    $file = public_path('UserManagement/template-registrasi-dosen.xlsx');
    return Response::download($file, 'template-registrasi-dosen.xlsx');
})->name('download.template-dosen');

Route::get('/download-template-mahasiswa', function () {
    $file = public_path('UserManagement/template-registrasi-mahasiswa.xlsx');
    return Response::download($file, 'template-registrasi-mahasiswa.xlsx');
})->name('download.template-mhs');

// Route untuk aktifkan atau non-aktifkan akun
Route::post('/nonaktif-dosen', [DosenController::class, 'nonaktifkanAkun'])->name('nonaktif-dosen');
Route::post('/aktif-dosen', [DosenController::class, 'aktifkanAkun'])->name('aktif-dosen');

Route::post('/nonaktif-mhs', [MahasiswaController::class, 'nonaktifkanAkun'])->name('nonaktif-mhs');
Route::post('/aktif-mhs', [MahasiswaController::class, 'aktifkanAkun'])->name('aktif-mhs');

Route::post('/import-mhs', [MahasiswaController::class, 'import'])->name('import-mhs');
Route::post('/import-dosen', [DosenController::class, 'import'])->name('import-dosen');
Route::get('/previewDataDosen', [DosenController::class, 'previewDataDosen'])->name('previewDataDosen');

Route::post('/inputBulkDosen', [DosenController::class, 'inputBulk'])->name('inputBulkDosen');
Route::post('/updateBulkRole', [DosenController::class, 'updateBulkRole'])->name('updateBulkRole');

Route::get('/data-mahasiswa', [UserManagementController::class, 'show_mhs']);
Route::get('/data-kelompok-ta', [UserManagementController::class, 'show_kelompok_ta']);

Route::get('/detail-kota-prodi/{id}', [UserManagementController::class, 'getDetailKotaProdi']);



Route::middleware(['auth', 'can:all_mahasiswa'])->group(function () {
    Route::get('/perekrutan-anggota-kota', [PerekrutanAnggotaKoTAController::class, 'index'])->name('perekrutan-anggota-kota');
    Route::post('/perekrutan-anggota-kota', [PerekrutanAnggotaKoTAController::class, 'submit'])->name('perekrutan-anggota-kota.submit');
    Route::get('/konfirmasi-kota', [KonfirmasiKoTAController::class, 'index'])->name('konfirmasi-kota');
    Route::get('/kota-saya', [DetailKoTAController::class, 'index'])->name('kota.saya');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile')->middleware('auth');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
});


// Route::get('/test-spatie', function () {
//     $user = User::where('username', 'dosen001')->first(); // Sesuaikan dengan username yang ada

//     // Tambahkan role dan permission
//     return [
//         'has_dosen_role' => $user->hasRole('dosen'),
//         'can_edit_post' => $user->can('edit-profil')
//     ];
// }

// );


Route::middleware(['auth', 'can:admin'])->group(function () {
    // route untuk kelola KBK
    Route::get('/kelola-kbk', [KBKController::class, 'index'])->name('kelola-kbk');
    Route::post('/kelola-kbk', [KBKController::class, 'store'])->name('kelola-kbk.store');
    Route::post('/kelola-kbk/update/{id}', [KBKController::class, 'update'])->name('kelola-kbk.update');
    Route::delete('/kelola-kbk/{id}', [KBKController::class, 'destroy'])->name('kelola-kbk.destroy');
    Route::put('/kelola-kbk/update/{id}', [KBKController::class, 'update'])->name('kelola-kbk.update');

    // route untuk kelola program studi
    Route::get('/program-studi', [ProgramStudiController::class, 'index'])->name('program-studi.index');
    Route::get('/program-studi/create', [ProgramStudiController::class, 'create'])->name('program-studi.create');
    Route::post('/program-studi', [ProgramStudiController::class, 'store'])->name('program-studi.store');
    Route::get('/program-studi/{id}/edit', [ProgramStudiController::class, 'edit'])->name('program-studi.edit');
    Route::put('/program-studi/{id}', [ProgramStudiController::class, 'update'])->name('program-studi.update');
    Route::delete('/program-studi/{id}', [ProgramStudiController::class, 'destroy'])->name('program-studi.destroy');

});

// define the route for TokenVerify verifyToken
Route::get('/usermanagement/v1/role', [TokenVerify::class, 'verifyToken']);

Route::get('/external-service/ruangan', [TestServiceCallController::class, 'redirectToExternalService'])->name('test.service.call')->middleware('auth');

Route::middleware(['auth', 'can:koordinator_ta'])->group(function () {
    Route::get('/management-kota', [ManagementKoTAController::class, 'index'])->name('management-kota');
    Route::get('/detail-kota/{id}', [DetailKoTAController::class, 'index'])->name('detail.kota');
});

/* 

    Dashboard route
==========================================*/
Route::get('/test-dashboard', [DashboardController::class, 'showPengajuanSeminar3']);
Route::get('/', [DashboardController::class, 'index']);


Route::middleware(['can:mahasiswa_ta'])->group(function () {
    Route::get('/tambah-anggota-kota/{id}', [PerekrutanAnggotaKoTAController::class, 'showTambahAnggotaForm'])->name('tambah-anggota-kota');
    Route::post('/tambah-anggota-kota/{id}', [PerekrutanAnggotaKoTAController::class, 'tambahAnggota'])->name('tambah-anggota-kota.submit');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/impersonate/{id}', [ImpersonateController::class, 'impersonate'])->name('impersonate');
    Route::get('/impersonate-leave', [ImpersonateController::class, 'leave'])->name('impersonate.leave');
});
