<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [ApiController::class, 'loginAndRegister']);
Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('logout', [ApiController::class, 'logout']);
    Route::post('update-profile',[ApiController::class,'updateProfile']);
    // Route::get('treatment', [ApiController::class, 'getTreatment']);
    Route::get('popular-treatment', [ApiController::class, 'getPopularTreatment']);
    Route::get('category-treatment', [ApiController::class, 'getCategoryWithTreatment']);
    Route::get('availability', [ApiController::class, 'getAvailability']);
    Route::post('add-wishlist', [ApiController::class, 'addWishlist']);
    Route::get('get-wishlist', [ApiController::class, 'getWishlist']);
});
