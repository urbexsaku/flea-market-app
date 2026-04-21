<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ExhibitionController;

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
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'index']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store']);
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit']);
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'update']);
    Route::get('/sell', [ExhibitionController::class, 'index']);
});