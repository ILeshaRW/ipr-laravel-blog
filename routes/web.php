<?php

use App\Http\Controllers\Blog\CommentController;
use App\Http\Controllers\Blog\PostController;
use App\Http\Controllers\UserController;
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
    return view('welcome');
});

Route::get('post/{postId}', [PostController::class, 'getPostDetail'])->name('post');
Route::get('posts', [PostController::class, 'getPosts']);

Route::middleware('auth')->group(function () {
    Route::put('post/comment', [CommentController::class, 'createComment'])->name('create_comment');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [UserController::class, 'loginView'])->name('login');
    Route::post('login', [UserController::class, 'authenticate']);
    Route::get('register', [UserController::class, 'register'])->name('register');
    Route::post('register',[UserController::class, 'create'])->name('create');
});

Route::middleware('auth')->group(function () {
    Route::get('lk', [UserController::class, 'index'])->name('index');
    Route::get('logout', [UserController::class, 'logout']);
});
