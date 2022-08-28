<?php

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

Route::post('/requests', [\App\Http\Controllers\Api\V1\RequestController::class, 'index']);
Route::post('/login', [\App\Http\Controllers\Api\LoginController::class, 'login']);

Route::group(['middleware' => 'jwt.verify'], function (){
    Route::get('/requests', [\App\Http\Controllers\Api\V1\RequestController::class, 'getAllRequests']);
    Route::put('/requests/{id}', [\App\Http\Controllers\Api\V1\RequestController::class, 'postAnswer']);
});
