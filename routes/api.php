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
    Route::get('treatment', [ApiController::class, 'getTreatment']);
    Route::get('popular-treatment', [ApiController::class, 'getPopularTreatment']);
    Route::get('category-treatment', [ApiController::class, 'getCategoryWithTreatment']);
    Route::get('availability', [ApiController::class, 'getAvailability']);
    Route::post('add-wishlist', [ApiController::class, 'addWishlist']);
    Route::get('get-wishlist', [ApiController::class, 'getWishlist']);
    Route::post('coupon-verify', [ApiController::class, 'couponVerify']);
    Route::post('book-appointment', [ApiController::class, 'BookAppointment']);
    Route::get('get-appointment', [ApiController::class, 'GetAppointment']);
    Route::get('questionAnswer', [ApiController::class, 'QuestionAnswer']);
    Route::get('get-pages', [ApiController::class, 'GetPages']);
    Route::get('get-in-touch', [ApiController::class, 'GetInTouch']);
    Route::get('get-articles', [ApiController::class, 'GetArticles']);
    //Doctor Api Start
    Route::get('get-all-appointment', [ApiController::class, 'GetAllAppointment']);
    Route::post('mark-appointment-status', [ApiController::class, 'markAppointmentStatus']);
    Route::get('get-coupons', [ApiController::class, 'GetCoupons']);
    Route::post('add-coupons', [ApiController::class, 'AddCoupons']);
    Route::post('edit-coupons', [ApiController::class, 'EditCoupons']);
    Route::post('delete-coupons', [ApiController::class, 'DeleteCoupons']);
    Route::post('get-all-payments', [ApiController::class, 'getAllPayments']);
});
