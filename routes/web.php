<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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


/*
|--------------------------------------------------------------------------
| HALAMAN UTAMA
|--------------------------------------------------------------------------
*/

Route::get('/', [WelcomeController::class, 'index'])
    ->name('welcome');


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
|
| Login dibuat langsung di web.php untuk sementara.
| Tidak menggunakan Breeze auth.php.
|
*/

// Route::get('/login', function () {
//     return view('auth.login');
// })->name('login');


// Route::post('/login', function (Request $request) {

//     /*
//     |--------------------------------------------------------------------------
//     | VALIDASI
//     |--------------------------------------------------------------------------
//     */

//     $credentials = $request->validate([
//         'email' => [
//             'required',
//             'email',
//         ],

//         'password' => [
//             'required',
//         ],
//     ]);


//     /*
//     |--------------------------------------------------------------------------
//     | LOGIN
//     |--------------------------------------------------------------------------
//     */

//     if (!Auth::attempt(
//         [
//             'email' => $credentials['email'],
//             'password' => $credentials['password'],
//         ],
//         $request->boolean('remember')
//     )) {

//         return back()
//             ->withInput($request->only('email', 'remember'))
//             ->withErrors([
//                 'email' => 'Email atau password salah.',
//             ]);
//     }


//     /*
//     |--------------------------------------------------------------------------
//     | REGENERATE SESSION
//     |--------------------------------------------------------------------------
//     */

//     $request->session()->regenerate();


//     /*
//     |--------------------------------------------------------------------------
//     | REDIRECT KE DASHBOARD
//     |--------------------------------------------------------------------------
//     */

//     return redirect()->route('admin.dashboard');

// })->name('login.store');


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

// Route::post('/logout', function (Request $request) {

//     Auth::logout();

//     $request->session()->invalidate();

//     $request->session()->regenerateToken();

//     return redirect()->route('login');

// })->middleware('auth')->name('logout');


/*
|--------------------------------------------------------------------------
| FORM TAMU
|--------------------------------------------------------------------------
*/

Route::get('/cek-nohp', [FormTamuController::class, 'cekNoHp'])
    ->name('form-tamu.cek-nohp');

Route::get('/form-tamu', [FormTamuController::class, 'create'])
    ->name('form-tamu');

Route::post('/form-tamu', [FormTamuController::class, 'store'])
    ->name('form-tamu.store');


/*
|--------------------------------------------------------------------------
| FEEDBACK
|--------------------------------------------------------------------------
*/

Route::get('/feedback/cek-nohp', [FeedbackController::class, 'cekNoHp'])
    ->name('feedback.cek-nohp');

Route::get('/feedback/{transaction}', [FeedbackController::class, 'create'])
    ->name('feedback.create');

Route::post('/feedback', [FeedbackController::class, 'store'])
    ->name('feedback.store');


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
|
| Untuk sementara hanya menggunakan middleware auth.
|
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | PENDIDIKAN
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | PEKERJAAN
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | MEDIA PELAYANAN
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | JENIS PELAYANAN
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | KATEGORI
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | SUB KATEGORI
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | PENGUNJUNG
        |--------------------------------------------------------------------------
        */

        Route::get('/customer/export-pdf', [CustomerController::class, 'exportPDF'])
    ->name('customer.export.pdf');

Route::get('/customer/export/{format?}', [CustomerController::class, 'exportExcel'])
    ->name('customer.export.excel');

Route::get('/customer', [CustomerController::class, 'index'])
    ->name('customer.index');

Route::get('/customer/{customer}', [CustomerController::class, 'show'])
    ->name('customer.show');


        /*
        |--------------------------------------------------------------------------
        | TUJUAN
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI
        |--------------------------------------------------------------------------
        */

Route::get('/transaction', [TransactionController::class, 'index'])
    ->name('transaction.index');

Route::get('/transaction/{transaksi}', [TransactionController::class, 'show'])
    ->name('transaction.show');

Route::put('/transaction/{transaksi}/update-status', [TransactionController::class, 'updateStatus'])
    ->name('transaction.updateStatus');

// Route::delete('/transaction/{transaksi}', [TransactionController::class, 'destroy'])
//     ->name('transaction.destroy');
    });


/*
|--------------------------------------------------------------------------
| SUPER ADMIN
|--------------------------------------------------------------------------
|
| Untuk sementara hanya menggunakan auth.
|
*/

Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware('auth')
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