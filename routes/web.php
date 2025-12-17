<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Include test routes (remove in production)
if (app()->environment('local')) {
    require __DIR__.'/test-email.php';
    require __DIR__.'/test-whatsapp.php';
}

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public Certificate View Route
Route::get('/certificate/{certificate}', [App\Http\Controllers\Admin\CertificateController::class, 'view'])
    ->name('certificate.view');

// Public Application Routes (basic throttling)
Route::prefix('apply')->name('apply.')->middleware('throttle:30,1')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\FormController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Public\FormController::class, 'store'])->middleware('throttle:60,1')->name('store');
    Route::get('/success/{token}', [App\Http\Controllers\Public\FormController::class, 'success'])->name('success');
    Route::get('/application/{token}', [App\Http\Controllers\Public\FormController::class, 'show'])->name('show');
    Route::post('/application/{upload}/delete', [App\Http\Controllers\Public\FormController::class, 'deleteUpload'])->name('upload.delete');

    // Trustee Verification Routes
    Route::get('/verify-trustee/{token}', [App\Http\Controllers\Public\TrusteeVerificationController::class, 'show'])->name('trustee.verify.show');
    Route::post('/verify-trustee/{token}', [App\Http\Controllers\Public\TrusteeVerificationController::class, 'update'])->name('trustee.verify.update');
});

require __DIR__.'/auth.php';

// Admin Routes (protected by auth + role)
Route::middleware(['auth','role:Super Admin|Verifier|Certificate Issuer'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/applicant-stats', [App\Http\Controllers\Admin\DashboardController::class, 'applicantStats'])->name('applicant-stats');
    // Documents/Uploads Management
    Route::get('/uploads', [App\Http\Controllers\Admin\UploadController::class, 'index'])->name('uploads.index');
        Route::get('/uploads-export', [App\Http\Controllers\Admin\UploadController::class, 'exportCsv'])->name('uploads.export');
    Route::get('/uploads/{upload}/view', [App\Http\Controllers\Admin\UploadController::class, 'view'])->name('uploads.view');
    Route::post('/uploads/{upload}/verify', [App\Http\Controllers\Admin\UploadController::class, 'verify'])->name('uploads.verify');
    Route::post('/uploads/{upload}/reject', [App\Http\Controllers\Admin\UploadController::class, 'reject'])->name('uploads.reject');
    // Certificates Management
    Route::get('/certificates', [\App\Http\Controllers\Admin\CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/certificates-export', [\App\Http\Controllers\Admin\CertificateController::class, 'exportCsv'])->name('certificates.export');
    Route::get('/certificates/{certificate}/view', [\App\Http\Controllers\Admin\CertificateController::class, 'view'])->name('certificates.view');
    Route::get('/certificates/{certificate}/download', [\App\Http\Controllers\Admin\CertificateController::class, 'download'])->name('certificates.download');
    Route::post('/certificates/{certificate}/send-email', [\App\Http\Controllers\Admin\CertificateController::class, 'sendEmail'])->name('certificates.send-email');
    Route::post('/certificates/{certificate}/send-whatsapp', [\App\Http\Controllers\Admin\CertificateController::class, 'sendWhatsApp'])->name('certificates.send-whatsapp');
    Route::post('/certificates/{certificate}/reset', [\App\Http\Controllers\Admin\CertificateController::class, 'resetStatus'])->name('certificates.reset-status');
    // Users Management (Super Admin only)
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        
        // User status management
        Route::post('/users/{user}/deactivate', [App\Http\Controllers\Admin\UserController::class, 'deactivate'])->name('users.deactivate');
        Route::post('/users/{user}/activate', [App\Http\Controllers\Admin\UserController::class, 'activate'])->name('users.activate');
        
        // User roles management
        Route::post('/users/{user}/roles', [App\Http\Controllers\Admin\UserController::class, 'updateRoles'])->name('users.update-roles');
        
        // User soft delete/restore
        Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/restore', [App\Http\Controllers\Admin\UserController::class, 'restore'])->name('users.restore');
    });
});


// Applicants Management (protected by auth + role)
Route::middleware(['auth','role:Super Admin|Verifier|Certificate Issuer'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/applicants', [\App\Http\Controllers\Admin\ApplicantController::class, 'index'])->name('applicants.index');
    Route::get('/applicants-export', [\App\Http\Controllers\Admin\ApplicantController::class, 'exportCsv'])->name('applicants.export');
    Route::get('/applicants/{applicant}', [\App\Http\Controllers\Admin\ApplicantController::class, 'show'])->name('applicants.show');
    Route::get('/applicants/{applicant}/edit', [\App\Http\Controllers\Admin\ApplicantController::class, 'edit'])->name('applicants.edit');
    Route::put('/applicants/{applicant}', [\App\Http\Controllers\Admin\ApplicantController::class, 'update'])->name('applicants.update');
    Route::post('/applicants/{applicant}/start-verification', [\App\Http\Controllers\Admin\ApplicantController::class, 'startVerification'])->name('applicants.start-verification');
    Route::post('/applicants/{applicant}/complete-verification', [\App\Http\Controllers\Admin\ApplicantController::class, 'completeVerification'])->name('applicants.complete-verification');
    Route::post('/applicants/{applicant}/reject', [\App\Http\Controllers\Admin\ApplicantController::class, 'reject'])->name('applicants.reject');
    Route::post('/applicants/{applicant}/generate-certificate', [\App\Http\Controllers\Admin\ApplicantController::class, 'generateCertificate'])->name('applicants.generate-certificate');
    Route::post('/applicants/{applicant}/send-trustee-verification', [\App\Http\Controllers\Admin\ApplicantController::class, 'sendTrusteeVerification'])->name('applicants.send-trustee-verification');
    // Soft delete/restore Applicants
    Route::delete('/applicants/{applicant}', [\App\Http\Controllers\Admin\ApplicantController::class, 'destroy'])->name('applicants.destroy');
    Route::post('/applicants/{id}/restore', [\App\Http\Controllers\Admin\ApplicantController::class, 'restore'])->name('applicants.restore');
});
