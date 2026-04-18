<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;

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

Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'show']);

Route::middleware('auth')->group(function() {
  Route::get('/mypage', [MypageController::class, 'index']);
  Route::get('/mypage/profile', [ProfileController::class, 'edit']);
  Route::post('/mypage/profile', [ProfileController::class, 'update']);
  Route::post('/like/{item_id}', [LikeController::class, 'toggle']);
  Route::post('/comment/{item_id}', [CommentController::class, 'store']);
});
