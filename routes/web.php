<?php

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
});

Route::prefix('export')->name('export.')->group(function () {
    Route::get('users/export', [UserController::class, 'export'])->name('user-export');
});

Route::prefix('exportpdf')->name('exportpdf.')->group(function () {
    Route::get('users/export', [UserController::class, 'exportPDF'])->name('user-exportpdf');
});



