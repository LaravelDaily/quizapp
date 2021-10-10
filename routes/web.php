<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppUserController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\AppUserDashboardController;

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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {

    //Route::resource('users', ManageUserController::class);

    Route::get('/users', [ManageUserController::class, 'index'])->name('usersIndex');

    Route::get('/adminhome', [AdminController::class, 'index'])->name('adminhome');

    Route::resource('sections', SectionsController::class);

    // Nemenau, kad dėl 2 route verta šitam resource daryt, nes create ir store nestandartiniai pas jį
    Route::resource('questions', QuestionsController::class)->only(['show', 'destroy']);
    Route::get('questions/create/{section}', [QuestionsController::class, 'create'])->name('questions.create');
    Route::post('questions/store/{section}', [QuestionsController::class, 'store'])->name('questions.store');
    /*Route::get('questions/{question}', [QuestionsController::class, 'show'])->name('questions.show');
    Route::delete('questions/{question}', [QuestionsController::class, 'destroy'])->name('questions.destroy');*/
});

Route::middleware(['auth', 'verified', 'role:admin|user'])->prefix('appuser')->group(function () {

    Route::get('/userQuizHome', AppUserDashboardController::class)
        ->name('userQuizHome');

    Route::resource('quiz', AppUserController::class)->only(['create', 'show', 'destroy']);
});
