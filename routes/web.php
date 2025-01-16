<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\ProductController;


Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/oauth/redirect', [OAuthController::class, 'redirectToProvider'])->name('oauth.redirect');
Route::get('/oauth/callback', [OAuthController::class, 'handleProviderCallback'])->name('oauth.callback');
