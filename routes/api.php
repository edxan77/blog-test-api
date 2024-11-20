<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('/register', [\App\Http\Controllers\Api\RegisterController::class, 'register']);
    Route::post('/confirm', [\App\Http\Controllers\Api\RegisterController::class, 'confirm']);
    Route::post('/login', [\App\Http\Controllers\Api\LoginController::class, 'login']);
    Route::post('/confirmation/send', [\App\Http\Controllers\Api\RegisterController::class, 'sendConfirmationMessage']);
    Route::get('/blogs', [\App\Http\Controllers\Api\BlogController::class, 'getBlogs']);
    Route::get('/blog/{id}', [\App\Http\Controllers\Api\BlogController::class, 'getBlog']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/blog/add', [\App\Http\Controllers\Api\BlogController::class, 'createBlog']);
    Route::post('/blog/update', [\App\Http\Controllers\Api\BlogController::class, 'updateBlog']);
    Route::post('/blog/delete', [\App\Http\Controllers\Api\BlogController::class, 'deleteBlog']);
    Route::post('/logout', [\App\Http\Controllers\Api\LoginController::class, 'logout']);
});

