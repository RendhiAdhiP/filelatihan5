<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('/v1')->group(function(){
    Route::prefix('/auth')->group(function(){
        Route::post('/login',[AuthController::class, 'login']);
        Route::post('/logout',[AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/forms',[FormController::class, 'postform']);
        Route::get('/forms',[FormController::class, 'getform']);
        Route::get('/forms/{slug}',[FormController::class, 'getdetail']);

        Route::post('/forms/{slug}/questions',[QuestionController::class, 'addQuest']);
        Route::delete('/forms/{slug}/questions/{id}',[QuestionController::class, 'delete']);
        
        Route::post('/forms/{slug}/responses',[ResponseController::class, 'responseAnswer']);

    });
});