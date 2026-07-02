<?php

use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/site', [SiteController::class, 'show']);
Route::get('/home', [SiteController::class, 'home']);
Route::get('/legal/{slug}', [SiteController::class, 'legal']);

Route::get('/blog', [ContentController::class, 'blog']);
Route::get('/blog/{slug}', [ContentController::class, 'blogShow']);
Route::get('/projects', [ContentController::class, 'projects']);
Route::get('/projects/{slug}', [ContentController::class, 'projectShow']);
Route::get('/services', [ContentController::class, 'services']);
Route::get('/products', [ContentController::class, 'products']);

Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:10,1');
Route::post('/chat', [ChatController::class, 'store'])->middleware('throttle:20,1');
Route::post('/orders', [OrderController::class, 'store'])->middleware('throttle:10,1');
Route::post('/mercadopago/webhook', [OrderController::class, 'webhook']);
