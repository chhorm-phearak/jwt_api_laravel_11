<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SlideController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Route::middleware('auth:api')->group(function () {
//     Route::controller(AuthController::class)->group(function () {
//         Route::get('logout', 'logout');
//         Route::get('send-verify-mail/{email}', 'sendVerifyMail');
//         Route::get('verify-mail/{email}', 'verificationMail');
//         Route::get('profile', 'user_Profile');
//     });
//     Route::controller(SlideController::class)->group(function () {
//         Route::get('slide-index', 'index');
//         Route::get('slide-show/{id}', 'show');
//         Route::post('slide-store', 'store');
//         Route::get('slide-edit/{id}', 'edit');
//         Route::post('slide-update/{id}', 'update');
//         Route::post('slide-delete/{id}', 'delete');
//     });
//     Route::controller(ProductController::class)->group(function () {
//         Route::get('product-index', 'index');
//         Route::get('product-show/{id}', 'show');
//         Route::post('product-store', 'store');
//         Route::get('product-edit/{id}', 'edit');
//         Route::post('product-update/{id}', 'update');
//         Route::post('product-delete/{id}', 'delete');
//     });
//     Route::controller(CategoryController::class)->group(function () {
//         Route::get('category-index', 'index');
//         Route::get('category-show/{id}', 'show');
//         Route::post('category-store', 'store');
//         Route::get('category-edit/{id}', 'edit');
//         Route::post('category-update/{id}', 'update');
//         Route::post('category-delete/{id}', 'delete');
//         Route::post('category-search', 'search');
//     });
//     Route::controller(OrderController::class)->group(function () {
//         Route::get('order-index', 'index');
//         Route::get('order-show/{id}', 'show');
//         Route::post('order-store', 'store');
//         Route::get('order-edit/{id}', 'edit');
//         Route::post('order-update/{id}', 'update');
//         Route::post('order-delete/{id}', 'delete');
//     });
// });

Route::controller(AuthController::class)->group(function () {
    Route::get('logout', 'logout');
    Route::get('send-verify-mail/{email}', 'sendVerifyMail');
    Route::get('verify-mail/{email}', 'verificationMail');
    Route::get('profile', 'user_Profile');
});
Route::controller(SlideController::class)->group(function () {
    Route::get('slide-index', 'index');
    Route::get('slide-show/{id}', 'show');
    Route::post('slide-store', 'store');
    Route::get('slide-edit/{id}', 'edit');
    Route::post('slide-update/{id}', 'update');
    Route::post('slide-delete/{id}', 'delete');
});
Route::controller(ProductController::class)->group(function () {
    Route::get('product-index', 'index');
    Route::get('product-show/{id}', 'show');
    Route::post('product-store', 'store');
    Route::get('product-edit/{id}', 'edit');
    Route::post('product-update/{id}', 'update');
    Route::post('product-delete/{id}', 'delete');
});
Route::controller(CategoryController::class)->group(function () {
    Route::get('category-index', 'index');
    Route::get('category-show/{id}', 'show');
    Route::post('category-store', 'store');
    Route::get('category-edit/{id}', 'edit');
    Route::post('category-update/{id}', 'update');
    Route::post('category-delete/{id}', 'delete');
    Route::post('category-search', 'search');
});
Route::controller(OrderController::class)->group(function () {
    Route::get('order-index', 'index');
    Route::get('order-show/{id}', 'show');
    Route::post('order-store', 'store');
    Route::get('order-edit/{id}', 'edit');
    Route::post('order-update/{id}', 'update');
    Route::post('order-delete/{id}', 'delete');
});
Route::controller(BrandController::class)->group(function () {
    Route::get('brand-index', 'index');
    Route::get('brand-show/{id}', 'show');
    Route::post('brand-store', 'store');
    Route::get('brand-edit/{id}', 'edit');
    Route::post('brand-update/{id}', 'update');
    Route::post('brand-delete/{id}', 'delete');
});
