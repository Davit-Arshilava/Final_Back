<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserQuizController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('quizzes', QuizController::class);
    Route::apiResource('questions', QuestionController::class);


    //other users quizzes
    Route::prefix('users-quizzes')->group(function () {
        Route::get('/', [UserQuizController::class, 'index']);
        Route::post('{id}/start', [UserQuizController::class, 'start']);
        Route::post('{id}/{question}/check/{answer}', [UserQuizController::class, 'check']);
        Route::post('{id}/end', [UserQuizController::class, 'end']);
    });

    //admin routes
    Route::middleware(['is_admin'])->group(function () {

        Route::prefix('admin')->group(function () {
            Route::get('quizzes', [AdminController::class, 'index']);
            Route::put('quizzes/{id}', [AdminController::class, 'update']);
        });
    });
});

