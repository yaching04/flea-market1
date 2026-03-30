<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ======================
// 認証不要ルート（誰でも見れる）
// ======================

// トップページ（商品一覧）
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// 商品詳細ページ
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

// ======================
// 認証必須ルート
// ======================
Route::middleware('auth')->group(function () {

    // 出品関連
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    // 購入関連
    Route::get('/purchase/{item}', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchases.store');

    // マイページ関連
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [MypageController::class, 'editProfile'])->name('mypage.profile.edit');
    Route::put('/mypage/profile', [MypageController::class, 'updateProfile'])->name('mypage.profile.update');

    // コメント関連（後で追加）
    // Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('comments.store');

    // いいね関連（後で追加）
    // Route::post('/items/{item}/like', [LikeController::class, 'toggle'])->name('likes.toggle');
});

// Fortifyの認証ルート（ログイン・登録・ログアウトなど）は自動で追加されるのでここには書かない
