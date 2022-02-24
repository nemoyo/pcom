<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PointController;

use Illuminate\Support\Facades\Auth;


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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', function () {
//     return view('home');
// });

// Route::get('/entry', [PointController::class, 'show'])->middleware('auth');

// 未ログインの場合
Route::middleware(['guest'])->group(function(){
    // 画面を返すだけ
    // 未ログインの場合、リダイレクトする先は ->name('login'); が付いているルートになる
    // app/Http/Middleware/Authenticate.phpファイルのredirectTo関数を更新することで変更できる
    Route::get('/', function () {
        return view('login');
    })->name('login');
    // AuthControllerクラスのloginメソッドに処理を渡す
    // ※useで呼び出すControllerを記載し忘れないこと
    Route::post('login', [AuthController::class, 'login'])->name('exeLogin');

});

// ログイン済みの場合
Route::middleware(['auth'])->group(function(){
    // Route::get('/home', function () {
    //     // dd(Auth::user());
    //     return view('home');
    // })->name('home');
    // ホーム画面
    Route::get('/home', [PointController::class, 'index'])->name('home');
    // 新規画面
    Route::get('/entry', [PointController::class, 'show']);
    // 編集画面
    Route::get('/edit/{id}', [PointController::class, 'edit'])->name('edit');

    // ポイントの登録
    Route::post('storePoint', [PointController::class, 'create'])->name('storePoint');
    // ポイントの更新
    Route::post('updatePoint', [PointController::class, 'update'])->name('updatePoint');
    // ポイントの削除
    Route::post('deletePoint', [PointController::class, 'delete'])->name('deletePoint');


    // ポイントマスタを取得するAPI
    Route::get('/point/{id}', [PointController::class, 'getDetail']);

    // ログアウト
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
