<?php

use App\Http\Middleware\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route::post('/login',     'App\Http\Controllers\Api\AuthController@login')->middleware();
Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');

Route::middleware([JWT::class])->group(function() {
    // Route::get ('/auth',     'App\Http\Controllers\Api\AuthController@auth');
    Route::get ('/accounts', 'App\Http\Controllers\Api\AccountsApiController@getRecords')->middleware('api');
    Route::post('/accounts', 'App\Http\Controllers\Api\AccountsApiController@createRecord');
    Route::post('/deals',    'App\Http\Controllers\Api\DealsApiController@createRecord');
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
