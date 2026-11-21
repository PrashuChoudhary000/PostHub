<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MyPostController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/my-posts', [MyPostController::class, 'index'])->name('my-posts.index');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->name('posts.comments.store');

    Route::resource('posts', PostController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
});

Route::resource('posts', PostController::class)->only(['index', 'show']);
