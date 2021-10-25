<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'loan'
], function ($router) {
    Route::post('', [LoanController::class, 'post'])->middleware(['roleChecker:customer,ba']);
    Route::put('{id}', [LoanController::class, 'put'])->middleware(['roleChecker:null,ba']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'payment'
], function ($router) {
    Route::get('generatePaymentRequest', [PaymentController::class, 'generatePaymentRequest']);
    Route::get('{id}', [PaymentController::class, 'get'])->middleware(['roleChecker:customer,ba']);
    Route::put('{id}', [PaymentController::class, 'put']);
});
