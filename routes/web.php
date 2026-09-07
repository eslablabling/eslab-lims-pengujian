<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LablingAuthMiddleware;
use App\Http\Middleware\ClientAuthMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ClientLoginController;
use App\Http\Controllers\Auth\SsoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CocController;
use App\Http\Controllers\SamplingController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\AnalisaController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\KomunikasiController;
use App\Http\Controllers\KelolaUsersController;
use App\Http\Controllers\KelolaKlienController;
use App\Http\Controllers\TrenController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\LoggerController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\PortalKlienController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\Pengujian\PengujianPermintaanController;
use App\Http\Controllers\Pengujian\PengujianJadwalController;
use App\Http\Controllers\Pengujian\PengujianInvoiceController;
use App\Http\Controllers\Pengujian\PengujianPengirimanController;
use App\Http\Controllers\Pengujian\PengujianFinanceController;
use App\Http\Controllers\AdminMaster\AdminMasterDashboardController;

// ─────────────────────────────────────────────
//  Redirection & Double Prefix Cleanup
// ─────────────────────────────────────────────
Route::get('/pengujian/pengujian/{path?}', function ($path = 'dashboard') {
    return redirect('/pengujian/' . ltrim($path, '/'), 301);
})->where('path', '.*');

// ─────────────────────────────────────────────
//  Staff Auth Routes
// ─────────────────────────────────────────────
Route::get('/', function () {
    return app(LoginController::class)->showLogin();
})->name('root.login');

Route::get('/login',             [LoginController::class, 'showLogin'])->name('login');
Route::post('/login',            [LoginController::class, 'login'])->name('login.post');
Route::get('/pengujian',         function () { return redirect('/login'); });
Route::get('/pengujian/login',   [LoginController::class, 'showLogin']);
Route::post('/pengujian/login',  [LoginController::class, 'login']);

Route::match(['get', 'post'], '/logout',           [LoginController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], '/pengujian/logout', [LoginController::class, 'logout'])->name('pengujian.logout');

Route::get('/sso-login',           [SsoController::class, 'handleSsoLogin'])->name('sso.login');
Route::get('/pengujian/sso-login', [SsoController::class, 'handleSsoLogin']);

// HTML redirects for backward compatibility
foreach (['', 'pengujian'] as $htmlPrefix) {
    $p = $htmlPrefix ? '/' . $htmlPrefix : '';
    Route::get($p . '/index.html',         [LoginController::class, 'showLogin']);
    Route::get($p . '/login-klien.html',   [ClientLoginController::class, 'showLogin']);
    Route::get($p . '/dashboard.html',     function () use ($p) { return redirect($p ? '/pengujian/dashboard' : '/dashboard'); });
    Route::get($p . '/master-data.html',   function () use ($p) { return redirect($p ? '/pengujian/master-data' : '/master-data'); });
    Route::get($p . '/kelola-klien.html',  function () use ($p) { return redirect($p ? '/pengujian/kelola-klien' : '/kelola-klien'); });
    Route::get($p . '/kelola-users.html',  function () use ($p) { return redirect($p ? '/pengujian/kelola-users' : '/kelola-users'); });
    Route::get($p . '/peralatan.html',     function () use ($p) { return redirect($p ? '/pengujian/peralatan' : '/peralatan'); });
    Route::get($p . '/coc.html',           function () use ($p) { return redirect($p ? '/pengujian/coc' : '/coc'); });
    Route::get($p . '/sampling.html',      function () use ($p) { return redirect($p ? '/pengujian/sampling' : '/sampling'); });
    Route::get($p . '/penerimaan.html',    function () use ($p) { return redirect($p ? '/pengujian/penerimaan' : '/penerimaan'); });
    Route::get($p . '/analisa.html',       function () use ($p) { return redirect($p ? '/pengujian/analisa' : '/analisa'); });
    Route::get($p . '/coa.html',           function () use ($p) { return redirect($p ? '/pengujian/coa' : '/coa'); });
    Route::get($p . '/komunikasi.html',    function () use ($p) { return redirect($p ? '/pengujian/komunikasi' : '/komunikasi'); });
    Route::get($p . '/tren.html',          function () use ($p) { return redirect($p ? '/pengujian/tren' : '/tren'); });
    Route::get($p . '/peta.html',          function () use ($p) { return redirect($p ? '/pengujian/peta' : '/peta'); });
    Route::get($p . '/logger.html',        function () use ($p) { return redirect($p ? '/pengujian/logger' : '/logger'); });
    Route::get($p . '/dokumen.html',       function () use ($p) { return redirect($p ? '/pengujian/dokumen' : '/dokumen'); });
    Route::get($p . '/portal-klien.html',  function () { return redirect()->route('pengujian.klien.portal'); });
}

// ─────────────────────────────────────────────
//  Client Portal Auth Routes
// ─────────────────────────────────────────────
Route::get('/klien',                  function () { return redirect('/pengujian/klien/login'); });
Route::get('/pengujian/klien',        function () { return redirect('/pengujian/klien/login'); });
Route::get('/klien/login',            [ClientLoginController::class, 'showLogin']);
Route::post('/klien/login',           [ClientLoginController::class, 'login']);
Route::get('/pengujian/klien/login',  [ClientLoginController::class, 'showLogin'])->name('klien.login');
Route::post('/pengujian/klien/login', [ClientLoginController::class, 'login'])->name('klien.login.post');
Route::match(['get', 'post'], '/klien/logout',           [ClientLoginController::class, 'logout']);
Route::match(['get', 'post'], '/pengujian/klien/logout', [ClientLoginController::class, 'logout'])->name('klien.logout');

// ─────────────────────────────────────────────
//  Generic API Bridge Routes for MySQL (PWA & Mobile APK)
// ─────────────────────────────────────────────
foreach (['', 'pengujian'] as $apiPrefix) {
    Route::prefix($apiPrefix)->group(function () {
        Route::get('/_api/{table}/query',   [ApiController::class, 'query']);
        Route::post('/_api/{table}/insert', [ApiController::class, 'insert']);
        Route::post('/_api/{table}/update', [ApiController::class, 'update']);
        Route::post('/_api/{table}/upsert', [ApiController::class, 'upsert']);
        Route::post('/_api/{table}/delete', [ApiController::class, 'delete']);
    });
}

// ─────────────────────────────────────────────
//  Client Portal (Session Protected)
// ─────────────────────────────────────────────
Route::middleware([ClientAuthMiddleware::class])->group(function () {
    Route::get('/portal',                 [PortalKlienController::class, 'indexPengujian'])->name('portal');
    Route::get('/portal-klien',           [PortalKlienController::class, 'indexPengujian'])->name('portal.klien');
    Route::get('/pengujian/portal',       [PortalKlienController::class, 'indexPengujian']);
    Route::get('/pengujian/klien/portal', [PortalKlienController::class, 'indexPengujian'])->name('pengujian.klien.portal');
    Route::get('/klien/portal',           [PortalKlienController::class, 'indexPengujian'])->name('klien.portal');

    // Primary named client actions
    Route::prefix('klien')->group(function () {
        Route::post('/upload-bukti-bayar',           [PortalKlienController::class, 'uploadBuktiBayar'])->name('klien.upload-bukti-bayar');
        Route::match(['get', 'post'], '/verify-doc', [PortalKlienController::class, 'verifyDocument'])->name('klien.verify-doc');
        Route::get('/pesan',                         [KomunikasiController::class, 'fetchForClient'])->name('klien.pesan.fetch');
        Route::post('/pesan',                        [KomunikasiController::class, 'sendFromClient'])->name('klien.pesan.send');
    });

    Route::prefix('pengujian/klien')->group(function () {
        Route::post('/upload-bukti-bayar',           [PortalKlienController::class, 'uploadBuktiBayar']);
        Route::match(['get', 'post'], '/verify-doc', [PortalKlienController::class, 'verifyDocument']);
        Route::get('/pesan',                         [KomunikasiController::class, 'fetchForClient']);
        Route::post('/pesan',                        [KomunikasiController::class, 'sendFromClient']);
    });
});

// ─────────────────────────────────────────────
//  Public Certificate & CoA Verification (QR Code Scan)
// ─────────────────────────────────────────────
Route::get('/verify-certificate/{qr_token}',    [CertificateVerificationController::class, 'verify'])->name('coa.verify');
Route::get('/api/verify-certificate-search',     [CertificateVerificationController::class, 'search'])->name('coa.verify.search');

// ─────────────────────────────────────────────
//  Staff Routes (Auth required)
// ─────────────────────────────────────────────
foreach (['', 'pengujian'] as $webPrefix) {
    Route::prefix($webPrefix)->middleware(['auth', LablingAuthMiddleware::class])->group(function () use ($webPrefix) {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name($webPrefix === '' ? 'dashboard' : 'pengujian.dashboard');

        // Quotation & Permintaan Pengujian
        Route::prefix('permintaan')->name($webPrefix === '' ? 'permintaan.' : 'pengujian.permintaan.')->group(function () {
            Route::get('/',              [PengujianPermintaanController::class, 'index'])->name('index');
            Route::post('/',             [PengujianPermintaanController::class, 'store'])->name('store');
            Route::put('/{id}/po',       [PengujianPermintaanController::class, 'updatePo'])->name('po.update');
            Route::get('/{id}/print',    [PengujianPermintaanController::class, 'printQuotation'])->name('print');
            Route::delete('/{id}',       [PengujianPermintaanController::class, 'destroy'])->name('destroy');
        });

        // Penerbitan Jadwal & Surat Tugas Sampling
        Route::prefix('jadwal')->name($webPrefix === '' ? 'jadwal.' : 'pengujian.jadwal.')->group(function () {
            Route::get('/',                      [PengujianJadwalController::class, 'index'])->name('index');
            Route::put('/{id}',                  [PengujianJadwalController::class, 'updateJadwal'])->name('update');
            Route::post('/bulk-update',          [PengujianJadwalController::class, 'bulkUpdate'])->name('bulk-update');
            Route::match(['get', 'post'], '/print-batch', [PengujianJadwalController::class, 'printBatch'])->name('print-batch');
            Route::get('/{id}/print',            [PengujianJadwalController::class, 'printSuratTugas'])->name('print');
        });

        // Invoicing & Kwitansi Pengujian
        Route::prefix('invoice')->name($webPrefix === '' ? 'invoice.' : 'pengujian.invoice.')->group(function () {
            Route::get('/',                      [PengujianInvoiceController::class, 'index'])->name('index');
            Route::post('/{id}/terbit',          [PengujianInvoiceController::class, 'terbitInvoice'])->name('terbit');
            Route::post('/{id}/batal',           [PengujianInvoiceController::class, 'batalInvoice'])->name('batal');
            Route::get('/{id}/print-invoice',    [PengujianInvoiceController::class, 'printInvoice'])->name('print.invoice');
            Route::get('/{id}/print-kwitansi',   [PengujianInvoiceController::class, 'printKwitansi'])->name('print.kwitansi');
            Route::get('/{id}/print-bast',       [PengujianInvoiceController::class, 'printBast'])->name('print.bast');
            Route::get('/{id}/print-tst',        [PengujianInvoiceController::class, 'printTst'])->name('print.tst');
            Route::get('/{id}/print-all',        [PengujianInvoiceController::class, 'printAll'])->name('print.all');
        });

        // Pengiriman Dokumen
        Route::prefix('pengiriman')->name($webPrefix === '' ? 'pengiriman.' : 'pengujian.pengiriman.')->group(function () {
            Route::get('/',              [PengujianPengirimanController::class, 'index'])->name('index');
            Route::put('/{id}',          [PengujianPengirimanController::class, 'updatePengiriman'])->name('update');
        });

        // Finance & Penagihan
        Route::prefix('finance')->name($webPrefix === '' ? 'finance.' : 'pengujian.finance.')->group(function () {
            Route::get('/',              [PengujianFinanceController::class, 'index'])->name('index');
            Route::put('/{id}',          [PengujianFinanceController::class, 'updatePembayaran'])->name('update');
        });

        // COC (Chain of Custody)
        Route::post('coc/{coc}/duplicate', [CocController::class, 'duplicate'])->name($webPrefix === '' ? 'coc.duplicate' : 'pengujian.coc.duplicate');
        Route::resource('coc', CocController::class)->names($webPrefix === '' ? 'coc' : 'pengujian.coc');

        // Sampling Lapangan
        Route::prefix('sampling')->name($webPrefix === '' ? 'sampling.' : 'pengujian.sampling.')->group(function () {
            Route::get('/',              [SamplingController::class, 'index'])->name('index');
            Route::post('/',             [SamplingController::class, 'store'])->name('store');
            Route::get('/{sample}',      [SamplingController::class, 'show'])->name('show');
            Route::get('/{sample}/edit', [SamplingController::class, 'edit'])->name('edit');
            Route::put('/{sample}',      [SamplingController::class, 'update'])->name('update');
            Route::delete('/{sample}',   [SamplingController::class, 'destroy'])->name('destroy');
        });

        // Penerimaan Sampel di Laboratorium
        Route::prefix('penerimaan')->name($webPrefix === '' ? 'penerimaan.' : 'pengujian.penerimaan.')->group(function () {
            Route::get('/',                  [PenerimaanController::class, 'index'])->name('index');
            Route::post('/batch-receive',    [PenerimaanController::class, 'batchReceive'])->name('batch-receive');
            Route::post('/{sample}/receive', [PenerimaanController::class, 'receive'])->name('receive');
        });

        // Analisa Parameter Laboratorium
        Route::prefix('analisa')->name($webPrefix === '' ? 'analisa.' : 'pengujian.analisa.')->group(function () {
            Route::get('/',                        [AnalisaController::class, 'index'])->name('index');
            Route::put('/{sample}',                [AnalisaController::class, 'update'])->name('update');
            Route::post('/{sample}/submit-verify', [AnalisaController::class, 'submitForVerification'])->name('submit-verify');
        });

        // CoA (Certificate of Analysis) Verification
        Route::prefix('coa')->name($webPrefix === '' ? 'coa.' : 'pengujian.coa.')->group(function () {
            Route::get('/',                       [CoaController::class, 'index'])->name('index');
            Route::post('/{sample}/verify',       [CoaController::class, 'verify'])->name('verify');
            Route::get('/preview/{coc}',          [CoaController::class, 'preview'])->name('preview');
            Route::get('/{coc}/preview',          [CoaController::class, 'preview'])->name('preview.alt');
            Route::post('/coc/{coc}/scanned-url', [CoaController::class, 'updateScannedUrl'])->name('update-scanned');
        });

        // Peralatan Uji
        Route::prefix('peralatan')->name($webPrefix === '' ? 'peralatan.' : 'pengujian.peralatan.')->group(function () {
            Route::get('/',               [PeralatanController::class, 'index'])->name('index');
            Route::post('/',              [PeralatanController::class, 'store'])->name('store');
            Route::put('/{peralatan}',    [PeralatanController::class, 'update'])->name('update');
            Route::delete('/{peralatan}', [PeralatanController::class, 'destroy'])->name('destroy');
            Route::post('/surat-jalan',   [PeralatanController::class, 'storeSuratJalan'])->name('surat-jalan.store');
        });

        // Komunikasi Klien
        Route::prefix('komunikasi')->name($webPrefix === '' ? 'komunikasi.' : 'pengujian.komunikasi.')->group(function () {
            Route::get('/',               [KomunikasiController::class, 'index'])->name('index');
            Route::post('/{message}/reply', [KomunikasiController::class, 'reply'])->name('reply');
            Route::delete('/{message}',   [KomunikasiController::class, 'destroy'])->name('destroy');
        });

        // Tren Mutu & Parameter
        Route::get('/tren',          [TrenController::class, 'index'])->name($webPrefix === '' ? 'tren.index' : 'pengujian.tren.index');
        Route::get('/tren/data',     [TrenController::class, 'getData'])->name($webPrefix === '' ? 'tren.data' : 'pengujian.tren.data');

        // Peta Titik Sampling GIS
        Route::get('/peta',          [PetaController::class, 'index'])->name($webPrefix === '' ? 'peta.index' : 'pengujian.peta.index');
        Route::get('/peta/data',     [PetaController::class, 'getData'])->name($webPrefix === '' ? 'peta.data' : 'pengujian.peta.data');

        // Logger / Audit Trail
        Route::get('/logger',        [LoggerController::class, 'index'])->name($webPrefix === '' ? 'logger.index' : 'pengujian.logger.index');

        // Dokumen & Arsip
        Route::get('/dokumen',       [DokumenController::class, 'index'])->name($webPrefix === '' ? 'dokumen.index' : 'pengujian.dokumen.index');

        // Master Data Pengujian
        Route::prefix('master-data')->name($webPrefix === '' ? 'master-data.' : 'pengujian.master-data.')->group(function () {
            Route::get('/',                [MasterDataController::class, 'index'])->name('index');
            Route::post('/',               [MasterDataController::class, 'store'])->name('store');
            Route::put('/{masterData}',    [MasterDataController::class, 'update'])->name('update');
            Route::delete('/{masterData}', [MasterDataController::class, 'destroy'])->name('destroy');
        });

        // SSO Cross-App Routes
        Route::get('/sso/hris', [SsoController::class, 'redirectToHris'])->name($webPrefix === '' ? 'sso.hris' : 'pengujian.sso.hris');

        // Admin Master Routes
        Route::middleware([RoleMiddleware::class . ':admin_master'])->prefix('admin-master')->name($webPrefix === '' ? 'admin-master.' : 'pengujian.admin-master.')->group(function () {
            Route::get('/dashboard',                   [AdminMasterDashboardController::class, 'index'])->name('dashboard');
            Route::post('/users',                      [AdminMasterDashboardController::class, 'storeUser'])->name('users.store');
            Route::put('/users/{id}',                  [AdminMasterDashboardController::class, 'updateUser'])->name('users.update');
            Route::post('/users/{id}/toggle-status',   [AdminMasterDashboardController::class, 'toggleUserStatus'])->name('users.toggle-status');
            Route::post('/users/{id}/reset-password',  [AdminMasterDashboardController::class, 'resetUserPassword'])->name('users.reset-password');
            Route::delete('/users/{id}',               [AdminMasterDashboardController::class, 'deleteUser'])->name('users.delete');
        });

        // Kelola Users
        Route::middleware([RoleMiddleware::class . ':admin_master'])->group(function () use ($webPrefix) {
            Route::prefix('kelola-users')->name($webPrefix === '' ? 'kelola-users.' : 'pengujian.kelola-users.')->group(function () {
                Route::get('/',            [KelolaUsersController::class, 'index'])->name('index');
                Route::post('/',           [KelolaUsersController::class, 'store'])->name('store');
                Route::put('/{user}',      [KelolaUsersController::class, 'update'])->name('update');
                Route::delete('/{user}',   [KelolaUsersController::class, 'destroy'])->name('destroy');
            });
        });

        // Kelola Klien
        Route::middleware([RoleMiddleware::class . ':admin_master,admin_ts,manager'])->group(function () use ($webPrefix) {
            Route::prefix('kelola-klien')->name($webPrefix === '' ? 'kelola-klien.' : 'pengujian.kelola-klien.')->group(function () {
                Route::get('/',                                  [KelolaKlienController::class, 'index'])->name('index');
                Route::post('/',                                 [KelolaKlienController::class, 'store'])->name('store');
                Route::match(['PUT', 'POST'], '/{klien}',        [KelolaKlienController::class, 'update'])->name('update');
                Route::post('/{klien}/toggle-status',            [KelolaKlienController::class, 'toggleStatus'])->name('toggle-status');
                Route::post('/{klien}/reset-password',           [KelolaKlienController::class, 'resetPassword'])->name('reset-password');
                Route::match(['DELETE', 'POST'], '/{klien}',     [KelolaKlienController::class, 'destroy'])->name('destroy');
            });
        });
    });
}
