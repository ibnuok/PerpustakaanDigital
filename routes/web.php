<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminDendaController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserPeminjamanController;
use App\Http\Controllers\UserDendaController;
use App\Http\Controllers\ProfileController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('buku', BukuController::class);
    Route::resource('kategori', KategoriController::class)->except(['show']);
    Route::resource('peminjaman', PeminjamanController::class);

    Route::post('/peminjaman/{peminjaman}/approve',
        [PeminjamanController::class, 'approve']
    )->name('peminjaman.approve');

    Route::post('/peminjaman/{peminjaman}/return',
        [PeminjamanController::class, 'markReturned']
    )->name('peminjaman.return');

    Route::get('/peminjaman/{peminjaman}/check-damage',
        [PeminjamanController::class, 'checkDamageForm']
    )->name('peminjaman.check-damage');

    Route::post('/peminjaman/{peminjaman}/save-damage',
        [PeminjamanController::class, 'saveDamage']
    )->name('peminjaman.save-damage');

    Route::resource('denda', AdminDendaController::class)->only(['index', 'show']);
    Route::post('/denda/{pengembalian}/approve', [AdminDendaController::class, 'approve'])->name('denda.approve');
    Route::post('/denda/{pengembalian}/reject', [AdminDendaController::class, 'reject'])->name('denda.reject');
    Route::get('/denda/{pengembalian}/bukti', [AdminDendaController::class, 'viewBukti'])->name('denda.view-bukti');
    Route::get('/denda/{pengembalian}/download-bukti', [AdminDendaController::class, 'downloadBukti'])->name('denda.download-bukti');
});


Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::get('/bukus', [UserPeminjamanController::class, 'bukus'])->name('bukus');

    // ✅ Daftarkan /create MANUAL sebelum resource agar tidak bentrok dengan {peminjaman}
    Route::get('/peminjaman/create', [UserPeminjamanController::class, 'create'])->name('peminjaman.create');

    Route::resource('peminjaman', UserPeminjamanController::class)->except(['show', 'create']);

    Route::post('/peminjaman/{peminjaman}/return',
        [UserPeminjamanController::class, 'return']
    )->name('peminjaman.return');

    Route::resource('denda', UserDendaController::class)->only(['index', 'show']);
    Route::get('/denda/{pengembalian}/payment', [UserDendaController::class, 'paymentForm'])->name('denda.payment');
    Route::post('/denda/{pengembalian}/submit-payment', [UserDendaController::class, 'submitPayment'])->name('denda.submit-payment');
    Route::get('/denda/history', [UserDendaController::class, 'history'])->name('denda.history');
});


Route::get('/', fn() => view('welcome'));

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
})
->middleware(['auth'])
->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';