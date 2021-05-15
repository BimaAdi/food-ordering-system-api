<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/auth/user', [AuthController::class, 'auth_user'])->middleware('auth:sanctum');
Route::get('/unauthorized', function(Request $request) {
    return response()->json([
        'message' => 'Unauthorized'
    ], 401);
})->name('unauthorized');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resources([
        'user' => UserController::class
    ]);
});
