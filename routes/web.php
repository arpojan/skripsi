<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SelectEnclosureController;
use App\Models\Enclosure;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ─── AUTH ROUTES ──────────────────────────────────────────────
Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    return redirect()->route('enclosure.select');
})->name('login.post');

Route::post('/logout', function () {
    return redirect()->route('login');
})->name('logout');

// ─── ENCLOSURE ROUTES ─────────────────────────────────────────
// GET: Tampilkan halaman pilih kandang (data dari DB via Controller)
Route::get('/select-enclosure', [SelectEnclosureController::class, 'index'])->name('enclosure.select');

// POST: Simpan pilihan kandang aktif ke session → redirect ke dashboard
Route::post('/select-enclosure/post', [SelectEnclosureController::class, 'store'])->name('enclosure.select.post');

// ─── DASHBOARD ROUTES ─────────────────────────────────────────
Route::get('/dashboard/{id?}', function ($id = null) {
    $enclosureName = 'Dasbor';
    if ($id) {
        $enclosure = Enclosure::find($id);
        if ($enclosure) {
            $enclosureName = $enclosure->name;
        }
    }
    return view('dashboard.index', compact('enclosureName'));
})->name('dashboard');

// ─── DEVELOPMENT / MISC ROUTES ────────────────────────────────
Route::get('/ojan', function () {
    return view('welcome');
})->name('ojan');