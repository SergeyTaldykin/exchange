<?php

use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\ContactUsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('index');
});

Route::post('/contact_us', [ContactUsController::class, 'store'])->name('contact_us');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

//Route::prefix('admin')->name('admin.')->group(function () {
//    Route::get('/', [ProfileController::class, 'index'])->name('index');
//});

Route::prefix('profile')->middleware(['auth'])->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::put('/', [ProfileController::class, 'update'])->name('update');

    Route::post('/avatar', [ProfileController::class, 'changeAvatar'])->name('avatar');
});



