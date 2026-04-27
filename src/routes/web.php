<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ExhibitionController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice'); //メール未認証ユーザーに認証案内画面を表示
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送信しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::post('/mypage/profile', [ProfileController::class, 'update']);
    Route::get('/mypage', [MypageController::class, 'index']);
    Route::post('/like/{item_id}', [LikeController::class, 'toggle']);
    Route::post('/comment/{item_id}', [CommentController::class, 'store']);

    Route::get('/purchase/success', [PurchaseController::class, 'success']);
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'index']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store']);
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit']);
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'update']);
    Route::post('/purchase/{item_id}/checkout', [PurchaseController::class, 'checkout']);

    Route::get('/sell', [ExhibitionController::class, 'index']);
    Route::post('/sell', [ExhibitionController::class, 'store']);
});
