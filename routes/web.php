<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FormTamuController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\WorkController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\CategorieController;
use App\Http\Controllers\Admin\SubCategorieController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PurposeController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\AdminController;

use App\Http\Middleware\SuperAdminMiddleware;

// Halaman utama
Route::get('/', [WelcomeController::class, 'index'])
    ->name('welcome');

// Form tamu
Route::get('/cek-nohp', [FormTamuController::class, 'cekNoHp'])
    ->name('form-tamu.cek-nohp');

Route::get('/form-tamu', [FormTamuController::class, 'create'])
    ->name('form-tamu');

Route::post('/form-tamu', [FormTamuController::class, 'store'])
    ->name('form-tamu.store');

// Feedback
Route::get('/feedback', function () {
    return view('feedback.index');
})->name('feedback.index');

Route::get('/feedback/cek-nohp', [FeedbackController::class, 'cekNoHp'])
    ->name('feedback.cek-nohp');

Route::get('/feedback/{transaction}', [FeedbackController::class, 'create'])
    ->name('feedback.create');

Route::post('/feedback', [FeedbackController::class, 'store'])
    ->name('feedback.store');

// Profile
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/dashboard/export-pdf', [DashboardController::class, 'exportPDF'])
            ->name('dashboard.export.pdf');

        Route::get('/dashboard/export-excel', [DashboardController::class, 'exportExcel'])
            ->name('dashboard.export.excel');

        // Pendidikan
        Route::get('/pendidikan', [EducationController::class, 'index'])
            ->name('education.index');

        Route::get('/pendidikan/create', [EducationController::class, 'create'])
            ->name('education.create');

        Route::post('/pendidikan', [EducationController::class, 'store'])
            ->name('education.store');

        Route::get('/pendidikan/{id}/edit', [EducationController::class, 'edit'])
            ->name('education.edit');

        Route::put('/pendidikan/{id}', [EducationController::class, 'update'])
            ->name('education.update');

        Route::delete('/pendidikan/{id}', [EducationController::class, 'destroy'])
            ->name('education.destroy');

        // Pekerjaan
        Route::get('/work', [WorkController::class, 'index'])
            ->name('work.index');

        Route::get('/work/create', [WorkController::class, 'create'])
            ->name('work.create');

        Route::post('/work', [WorkController::class, 'store'])
            ->name('work.store');

        Route::get('/work/{work}/edit', [WorkController::class, 'edit'])
            ->name('work.edit');

        Route::put('/work/{work}', [WorkController::class, 'update'])
            ->name('work.update');

        Route::delete('/work/{work}', [WorkController::class, 'destroy'])
            ->name('work.destroy');

        // Media
        Route::get('/media', [MediaController::class, 'index'])
            ->name('media.index');

        Route::get('/media/create', [MediaController::class, 'create'])
            ->name('media.create');

        Route::post('/media', [MediaController::class, 'store'])
            ->name('media.store');

        Route::get('/media/{id}/edit', [MediaController::class, 'edit'])
            ->name('media.edit');

        Route::put('/media/{id}', [MediaController::class, 'update'])
            ->name('media.update');

        Route::delete('/media/{id}', [MediaController::class, 'destroy'])
            ->name('media.destroy');

        // Service
        Route::get('/service', [ServiceController::class, 'index'])
            ->name('service.index');

        Route::get('/service/create', [ServiceController::class, 'create'])
            ->name('service.create');

        Route::post('/service', [ServiceController::class, 'store'])
            ->name('service.store');

        Route::get('/service/{id}/edit', [ServiceController::class, 'edit'])
            ->name('service.edit');

        Route::put('/service/{id}', [ServiceController::class, 'update'])
            ->name('service.update');

        Route::delete('/service/{id}', [ServiceController::class, 'destroy'])
            ->name('service.destroy');

        // Kategori
        Route::get('/categorie', [CategorieController::class, 'index'])
            ->name('categorie.index');

        Route::get('/categorie/create', [CategorieController::class, 'create'])
            ->name('categorie.create');

        Route::post('/categorie', [CategorieController::class, 'store'])
            ->name('categorie.store');

        Route::get('/categorie/{categorie}/edit', [CategorieController::class, 'edit'])
            ->name('categorie.edit');

        Route::put('/categorie/{categorie}', [CategorieController::class, 'update'])
            ->name('categorie.update');

        Route::delete('/categorie/{categorie}', [CategorieController::class, 'destroy'])
            ->name('categorie.destroy');

        // Sub Kategori
        Route::get('/subcategorie', [SubCategorieController::class, 'index'])
            ->name('subcategorie.index');

        Route::get('/subcategorie/create', [SubCategorieController::class, 'create'])
            ->name('subcategorie.create');

        Route::post('/subcategorie', [SubCategorieController::class, 'store'])
            ->name('subcategorie.store');

        Route::get('/subcategorie/{subcategorie}/edit', [SubCategorieController::class, 'edit'])
            ->name('subcategorie.edit');

        Route::put('/subcategorie/{subcategorie}', [SubCategorieController::class, 'update'])
            ->name('subcategorie.update');

        Route::delete('/subcategorie/{subcategorie}', [SubCategorieController::class, 'destroy'])
            ->name('subcategorie.destroy');

        // Customer
        Route::get('/customer/export-pdf', [CustomerController::class, 'exportPDF'])
            ->name('customer.export.pdf');

        Route::get('/customer/export/{format?}', [CustomerController::class, 'exportExcel'])
            ->name('customer.export.excel');

        Route::get('/customer', [CustomerController::class, 'index'])
            ->name('customer.index');

        Route::get('/customer/{customer}', [CustomerController::class, 'show'])
            ->name('customer.show');

        // Purpose
        Route::get('/purpose', [PurposeController::class, 'index'])
            ->name('purpose.index');

        Route::get('/purpose/create', [PurposeController::class, 'create'])
            ->name('purpose.create');

        Route::post('/purpose', [PurposeController::class, 'store'])
            ->name('purpose.store');

        Route::get('/purpose/{id}/edit', [PurposeController::class, 'edit'])
            ->name('purpose.edit');

        Route::put('/purpose/{id}', [PurposeController::class, 'update'])
            ->name('purpose.update');

        Route::delete('/purpose/{id}', [PurposeController::class, 'destroy'])
            ->name('purpose.destroy');

        // Transaction
        Route::get('/transaction', [TransactionController::class, 'index'])
            ->name('transaction.index');

        Route::get('/transaction/{transaksi}', [TransactionController::class, 'show'])
            ->name('transaction.show');

        Route::put('/transaction/{transaksi}/status', [TransactionController::class, 'updateStatus'])
            ->name('transaction.updateStatus');
        
        Route::get('/transaction/export/pdf', [TransactionController::class, 'exportPDF'])
            ->name('transaction.export.pdf');

        Route::get('/transaction/export/excel', [TransactionController::class, 'exportExcel'])
            ->name('transaction.export.excel');
    });

// Super Admin
Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', SuperAdminMiddleware::class])
    ->group(function () {

        Route::get('/admin', [AdminController::class, 'index'])
            ->name('admin.index');

        Route::get('/admin/create', [AdminController::class, 'create'])
            ->name('admin.create');

        Route::post('/admin', [AdminController::class, 'store'])
            ->name('admin.store');

        Route::get('/admin/{admin}/edit', [AdminController::class, 'edit'])
            ->name('admin.edit');

        Route::put('/admin/{admin}', [AdminController::class, 'update'])
            ->name('admin.update');

        Route::delete('/admin/{admin}', [AdminController::class, 'destroy'])
            ->name('admin.destroy');
    });

require __DIR__.'/auth.php';