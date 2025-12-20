<?php

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


use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MediaController;

// Welcome Page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Question Resource Routes
Route::resource('questions', QuestionController::class);

// Category Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// Media Upload Route for TinyMCE
Route::post('/upload-image', [MediaController::class, 'uploadImage'])->name('upload.image');
