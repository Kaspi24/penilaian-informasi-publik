<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EmailVerified;
use App\Http\Controllers\JuryController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\WorkUnitController;
use App\Http\Middleware\RespondentMiddleware;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Middleware\ProfileCompletedMiddleware;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/test-123', function () {
//     $token = 123456;
//     $expiry_time = '2024-06-14 23:00:00';

//     return new \App\Mail\VerifyEmail($token, $expiry_time);
//     // return view('welcome');
// });



Route::middleware('guest')->get('/', function () {
    return redirect('login');
});
Route::middleware('auth')->get('/', function () {
    return redirect('dashboard');
});

// Dashboard
Route::middleware(['auth'])->controller(DashboardController::class)->group(function() {
    Route::get('/dashboard', 'index')->name('dashboard');
});

// Admin Pages
Route::middleware(['auth', AdminMiddleware::class])->group(function() {
    // Work Units
    Route::get('/unit-kerja',   [WorkUnitController::class, 'index'])->name('work-unit.index');
    // Juries
    Route::get('/juri',         [JuryController::class, 'index'])->name('jury.index');
    Route::post('/juri',        [JuryController::class, 'store'])->name('jury.store');
    // Users
    Route::get('/pengguna',     [UserController::class, 'index'])->name('user.index');
    // Questions
    Route::get('/pertanyaan',   [QuestionController::class, 'index'])->withoutMiddleware(AdminMiddleware::class)->name('question.index');
});

// Questionnaire
Route::middleware(['auth', ProfileCompletedMiddleware::class, EmailVerified::class])->controller(QuestionnaireController::class)->as('questionnaire.')->group(function() {
    Route::get('penilaian',             'index')->name('index');
    Route::middleware(RespondentMiddleware::class)->group(function() {
        Route::get('isi-penilaian',         'start')->name('start');
        Route::put('update-answer',         'updateAnswer')->name('updateAnswer');
        Route::put('update-answer-child',   'updateAnswerChild')->name('updateAnswerChild');
        Route::put('submit-response',       'submitResponse')->name('submitResponse');
    });
    Route::get('show-score/{respondent_id}',        'showScore')->name('showScore');
    Route::get('evaluasi-penilaian/{respondent_id}',   'evaluate')->name('evaluate');
    Route::put('update-score/{respondent_id}',      'updateScore')->name('updateScore');
    Route::put('submit-score/{respondent_id}',      'submitScore')->name('submitScore');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile',          [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',        [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile-unit',   [ProfileController::class, 'updateWorkUnit'])->name('profile.updateWorkUnit');
    Route::delete('/profile',       [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::fallback(function () {
    abort(404);
});