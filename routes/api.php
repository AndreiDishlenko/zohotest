<?php

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

Route::get ('/accounts', 'App\Http\Controllers\Api\AccountsApiController@getRecords');
Route::post('/accounts', 'App\Http\Controllers\Api\AccountsApiController@createRecord');
Route::post('/deals',    'App\Http\Controllers\Api\DealsApiController@createRecord');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
