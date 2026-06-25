<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MobileAppApiController;

/*
|--------------------------------------------------------------------------
| Mobile App API Routes
|--------------------------------------------------------------------------
*/

// Public endpoints
Route::get('/opportunities', [MobileAppApiController::class, 'opportunities']);
Route::get('/opportunities/{id}', [MobileAppApiController::class, 'opportunityDetail']);
Route::get('/advertisements', [MobileAppApiController::class, 'advertisements']);
Route::get('/advertisements/{id}', [MobileAppApiController::class, 'advertisementDetail']);
Route::get('/featured-ads', [MobileAppApiController::class, 'featuredAds']);
Route::post('/advertisements/{id}/click', [MobileAppApiController::class, 'trackClick']);

// Authentication for mobile
Route::post('/register', [MobileAppApiController::class, 'register']);
Route::post('/login', [MobileAppApiController::class, 'login']);

// Protected mobile endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/advertisements/subscribe', [MobileAppApiController::class, 'subscribe']);
    Route::get('/my-subscriptions', [MobileAppApiController::class, 'mySubscriptions']);
    Route::post('/advertisements/renew/{id}', [MobileAppApiController::class, 'renewSubscription']);
});
