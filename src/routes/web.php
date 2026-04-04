<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\ProfileController;

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

Route::get('/mypage', [MyPageController::class, 'index'])->middleware('auth');

Route::get('/mypage/profle', [ProfileController::class, 'edit'])->middleware('auth');
Route::post('/mypage/profile', [ProfileController::class, 'update'])->middleware('auth');
