<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
  Route::get('/login', [AuthController::class, 'login'])->name('login');
  Route::get('/register', [AuthController::class, 'register'])->name('register');
  Route::post('/login', [AuthController::class, 'aksiLogin'])->name('aksi.login');
  Route::post('/register', [AuthController::class, 'aksiRegister'])->name('aksi.register');
});


Route::middleware('auth')->group(function () {

  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  Route::resource('books', BookController::class)
    ->middleware('role:admin,user');

  Route::resource('loans', LoanController::class)
    ->middleware('role:admin,user')
    ->parameters(['loans' => 'loan']);

  Route::resource('members', MemberController::class)
    ->middleware('role:admin')
    ->parameters(['members' => 'user']);

  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});