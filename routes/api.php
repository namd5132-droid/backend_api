<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\ContactController;

Route::post('/contact', [ContactController::class, 'store']);


Route::get('/showtimes', [ShowtimeController::class, 'index']);
Route::post('/showtimes', [ShowtimeController::class, 'store']);
Route::put('/showtimes/{id}', [ShowtimeController::class, 'update']);
Route::delete('/showtimes/{id}', [ShowtimeController::class, 'destroy']);


Route::get('/orders', [OrderController::class, 'index']);

Route::post('/checkout', [CheckoutController::class, 'checkout']);
Route::get('/bookings', [BookingController::class, 'index']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{id}', [BookingController::class, 'show']);
Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);



Route::get('/movies', [MovieController::class, 'index']);
Route::post('/movies', [MovieController::class, 'store']);
Route::get('/movies/{id}', [MovieController::class, 'show']);
Route::delete('/movies/{id}', [MovieController::class, 'destroy']);

Route::post('users/register', [AuthController::class, 'register']);
Route::post('users/login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);

Route::apiResource('users', UsersController::class);
Route::delete('/bookings/clear/{order_id}', [BookingController::class, 'clear']);
//admin
Route::get('/movies', [MovieController::class, 'index']);   // danh sách
Route::post('/movies', [MovieController::class, 'store']); // thêm
Route::get('/movies/{id}', [MovieController::class, 'show']); // chi tiết
Route::put('/movies/{id}', [MovieController::class, 'update']); // sửa
Route::delete('/movies/{id}', [MovieController::class, 'destroy']); // xóa



