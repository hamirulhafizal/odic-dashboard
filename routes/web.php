<?php

use App\Http\Controllers\Api\InvestmentController;
use App\Http\Controllers\Api\CommisionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Notifications\WelcomeEmailNotification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return redirect()->route('users.index');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::get('investments', [InvestmentController::class, 'index'])->name('investment.index');
    Route::get('investments/{id}', [InvestmentController::class, 'destroy'])->name('investment.destroy');
    Route::get('commisions', [CommisionController::class, 'index'])->name('commision.index');
    Route::get('commisions/{username}', [CommisionController::class, 'getByUsername'])->name('commision.all');
    Route::get('commisions/approval/{username}', [CommisionController::class, 'updateWithdrawStatus'])->name('commision.status');
    Route::put('investments/approval/{investment}', [InvestmentController::class, 'approval'])->name('investment.approval');
    Route::put('users/approved/{user}', [UserController::class, 'approved'])->name('users.approved');
});

Route::prefix('export')->name('export.')->group(function () {
    Route::get('users/export', [UserController::class, 'export'])->name('user-export');
    Route::get('investments/export', [InvestmentController::class, 'export'])->name('investment-export');
    Route::get('commisions/export', [CommisionController::class, 'export'])->name('commision-export');
});

Route::prefix('exportpdf')->name('exportpdf.')->group(function () {
    Route::get('users/export', [UserController::class, 'exportPDF'])->name('user-exportpdf');
    Route::get('investments/export', [InvestmentController::class, 'exportPDF'])->name('investment-exportpdf');
    Route::get('commisions/export', [CommisionController::class, 'exportPDF'])->name('commision-exportpdf');
});



