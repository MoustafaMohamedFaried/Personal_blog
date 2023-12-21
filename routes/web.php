<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if(Auth::check())
        return view('home');
    else
        return view('auth.login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::controller(UserController::class)->group(function () {
    Route::get('/users/show_img/{id}', [UserController::class,'show_img'])->name('users.show_img');
    Route::get('/users/archive', [UserController::class,'archive'])->name('users.archive');
    Route::post('/users/restore/{id}', [UserController::class,'restore'])->name('users.restore');
    Route::delete('/users/force_delete/{id}', [UserController::class,'force_delete'])->name('users.force_delete');
});
Route::resource('users', UserController::class);

Route::resource('posts', PostController::class);

Route::resource('comments', CommentController::class);
