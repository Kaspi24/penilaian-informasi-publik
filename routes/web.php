<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EmailVerified;
use App\Http\Controllers\JuryController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\WorkUnitController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\RespondentMiddleware;
use App\Http\Middleware\SuperadminMiddleware;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Middleware\ProfileCompletedMiddleware;
use App\Http\Middleware\ResponseNotSubmittedMiddleware;

// Route::get('/', [LandingPageController::class, 'index'])->name('welcome');
Route::controller(LandingPageController::class)->group(function(){
    Route::get('/',                 'index')->name('welcome');
    Route::middleware(['auth', SuperadminMiddleware::class])->group(function() {
        Route::get('manage-content',    'edit')->name('landing-page.edit');
        Route::post('manage-content',    'store')->name('landing-page.store');
        Route::put('manage-content',    'update')->name('landing-page.update');
        Route::put('update-sequence',   'updateSequence')->name('landing-page.updateSequence');
        Route::delete('manage-content', 'delete')->name('landing-page.delete');
    });
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
    Route::controller(JuryController::class)->as('jury.')->group(function() {
        Route::get('/juri',     'index')->name('index');
        Route::post('/juri',    'store')->name('store');
        Route::put('/juri',     'update')->name('update');
        Route::delete('/juri',  'delete')->name('delete');
    });
    // Admin
    Route::controller(AdminController::class)->as('admin.')->group(function() {
        Route::get('/admin',    'index')->name('index');
        Route::post('/admin',   'store')->name('store');
        Route::put('/admin',    'update')->name('update');
        Route::delete('/admin', 'delete')->name('delete');
    });
    // Users
    Route::controller(UserController::class)->as('user.')->group(function() {
        Route::get('/pengguna',    'index')->name('index');
        Route::put('/pengguna',    'update')->name('update');
        Route::delete('/pengguna', 'delete')->name('delete');
    });
    // Questions
    Route::get('/pertanyaan',   [QuestionController::class, 'index'])->withoutMiddleware(AdminMiddleware::class)->name('question.index');
});

// Questionnaire
Route::middleware(['auth', ProfileCompletedMiddleware::class, EmailVerified::class])->controller(QuestionnaireController::class)->as('questionnaire.')->group(function() {
    Route::get('penilaian',             'index')->name('index');
    Route::middleware(RespondentMiddleware::class)->group(function() {
        Route::get('isi-penilaian',         'start')->middleware(ResponseNotSubmittedMiddleware::class)->name('start');
        Route::put('update-answer',         'updateAnswer')->name('updateAnswer');
        Route::put('update-answer-child',   'updateAnswerChild')->name('updateAnswerChild');
        Route::put('submit-response',       'submitResponse')->name('submitResponse');
    });
    Route::get('show-score/{respondent_id}',            'showScore')->name('showScore');
    Route::get('evaluasi-penilaian/{respondent_id}',    'evaluate')->name('evaluate');
    Route::put('tetapkan-juri/{respondent_id}',         'setJury')->name('setJury');
    Route::put('update-score/{respondent_id}',          'updateScore')->name('updateScore');
    Route::put('submit-score/{respondent_id}',          'submitScore')->name('submitScore');
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