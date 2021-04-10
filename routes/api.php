<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SocialLoginController;
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

Route::post('login', [AuthController::class,'login']);



Route::post('/cadastro', [UserController::class,'store']);

Route::middleware(['apiJWT'])->group(function () {
    Route::get('/users', [UserController::class,'index']);
    Route::post('/logout', [AuthController::class,'logout']);
    Route::post('/me', [AuthController::class,'me']);
});

Route::post("/resetpassword", [UserController::class, 'resetarSenha']);

Route::post('/forgotpassword', [UserController::class, 'esqueciSenha']);

