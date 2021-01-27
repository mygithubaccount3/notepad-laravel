<?php

use App\Http\Controllers\NotesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user/{id}', [UsersController::class, 'get'])
    ->middleware('setlocale')->prefix('{locale}')->where(['locale' => '[a-zA-Z]{2}']);
Route::get('/locales/{lng}', function ($lng) {
    return file_get_contents("locales/${lng}/translation.json");
});

Route::middleware('guest2')->group(function () {
    Route::post('/login', [UsersController::class, 'logIn'])
        ->middleware(['api-login', 'throttle']);
    Route::post('/signup', [UsersController::class, 'signUp']);
});

Route::middleware(['auth:api', 'setlocale'])->prefix('{locale}')->where(['locale' => '[a-zA-Z]{2}'])->group(function () {
    Route::get('/logout', [UsersController::class, 'logOut']);
    Route::resource('notes', NotesController::class, [
        'parameters' => [
            'notes' => 'notes_uid'
        ],
        'except' => [
            'show'
        ]
    ]);//copy to web middleware
});

Route::get('/notes/{notes_uid}', [NotesController::class, 'show'])
    ->middleware('setlocale')->prefix('{locale}')->where(['locale' => '[a-zA-Z]{2}']);

Route::get('/email/verify', [
    UsersController::class, 'showVerificationPage'
])->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [
    UsersController::class, 'verifyEmail'
])->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', [
    UsersController::class, 'sendEmailVerificationLink'
])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
