<?php

use App\Http\Controllers\NotesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('guest2')->group(function() {
    Route::get('/login', [UsersController::class, 'showLoginPage'])->name('login');
    Route::post('/login', [UsersController::class, 'logIn']);
    Route::get('/signup', [UsersController::class, 'showSignupPage']);
    Route::post('/signup', [UsersController::class, 'signUp']);
});

Route::middleware(['auth:web', 'setlocale'])->prefix('{locale}')->where(['locale' => '[a-zA-Z]{2}'])->group(function() {
    Route::get('/logout', [UsersController::class, 'logOut']);//->middleware('auth:web');
    Route::resource('notes', NotesController::class, [
        'parameters' => [
            'notes' => 'notes_uid'
        ],
        'except' => [
            'show'
        ]
    ]);
});

Route::get('/notes/{notes_uid}', [NotesController::class, 'show'])
    ->name('notes.show');

Route::get('/email/verify', [
    UsersController::class, 'showVerificationPage'
])->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [
    UsersController::class, 'verifyEmail'
])->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', [
    UsersController::class, 'sendEmailVerificationLink'
])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::fallback(function () {
    return Auth::user() ? redirect('/en/notes') : redirect('/login');
});
