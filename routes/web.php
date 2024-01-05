<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Auth\LoginRegisterController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'web'], function () {
    Route::get('/register', [LoginRegisterController::class, 'register'])->name('register');
    Route::post('/store', [LoginRegisterController::class, 'store'])->name('store');
    Route::get('/login', [LoginRegisterController::class, 'login'])->name('login');
    Route::post('/authenticate', [LoginRegisterController::class, 'authenticate'])->name('authenticate');
    Route::get('/dashboard', [LoginRegisterController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [LoginRegisterController::class, 'logout'])->name('logout');
});


Route::resource('gallery', GalleryController::class);

Route::controller(GalleryController::class)->group(function () {
    Route::resource('gallery', GalleryController::class);
    Route::get('/create', 'create')->name('create');
    Route::get('/store', 'store')->name('store');
    Route::delete('delete/{id}', 'destroy')->name('destroy');
    Route::get('edit/{id}', 'edit')->name('edit');
    Route::patch('update/{id}', 'update')->name('update');
});