<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::namespace('Admin')->prefix('Admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('user', 'UserController');
    Route::match(['get', 'put'], 'profile', 'UserController@profile')->name('admin.profile');
    Route::match(['get', 'put'], 'change-password', 'UserController@updatePassword')->name('admin.password');
    Route::resource('page', 'PageController');
    Route::resource('template', 'TemplateController');
    Route::resource('category', 'CategoryController');
    Route::resource('treatment', 'TreatmentController');
});
