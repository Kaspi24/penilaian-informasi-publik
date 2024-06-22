<?php

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

Route::get('/test', function () {
    return view('auth.verify-email');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Questions
Route::middleware(['auth',ProfileCompletedMiddleware::class])->controller(QuestionController::class)->as('question.')->prefix('pertanyaan')->group(function() {
    Route::get('/', 'index')->name('index');
});

// Questionnaire
Route::middleware(['auth', ProfileCompletedMiddleware::class, EmailVerified::class])->controller(QuestionnaireController::class)->as('questionnaire.')->group(function() {
    Route::get('kuesioner',             'index')->name('index');
    Route::middleware(RespondentMiddleware::class)->group(function() {
        Route::get('isi-kuesioner',         'start')->name('start');
        Route::put('update-answer',         'updateAnswer')->name('updateAnswer');
        Route::put('update-answer-child',   'updateAnswerChild')->name('updateAnswerChild');
        Route::put('submit-response',       'submitResponse')->name('submitResponse');
    });
    Route::get('show-score/{respondent_id}',        'showScore')->name('showScore');
    Route::get('nilai-kuesioner/{respondent_id}',   'evaluate')->name('evaluate');
    Route::put('update-score/{respondent_id}',      'updateScore')->name('updateScore');
    Route::put('submit-score/{respondent_id}',      'submitScore')->name('submitScore');
});

// Work Units
Route::middleware(['auth',AdminMiddleware::class])->controller(WorkUnitController::class)->as('work-unit.')->prefix('unit-kerja')->group(function() {
    Route::get('/', 'index')->name('index');
});

// Users
Route::middleware(['auth',AdminMiddleware::class])->controller(UserController::class)->as('user.')->prefix('pengguna')->group(function() {
    Route::get('/', 'index')->name('index');
});

// Juries
Route::middleware(['auth',AdminMiddleware::class])->controller(JuryController::class)->as('jury.')->prefix('juri')->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile-unit', [ProfileController::class, 'updateWorkUnit'])->name('profile.updateWorkUnit');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::fallback(function () {
    abort(404);
});