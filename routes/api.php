<?php

use App\Http\Api\ApiController\ApiController;
use App\Http\Api\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('auth',[UserController::class,'login']);
Route::middleware('auth:api')->group(function (){
    Route::post('refresh',[UserController::class,'refresh']);
    Route::get('logout',[UserController::class,'logout']);
    Route::get('list',[ApiController::class,'get_list']);
});


Route::any('/{any}', function () {
    return  response()->view('error' , ["title" => "404 Route exception","message"=> "Route not found or method unsupported", "messageOriginal"=> null]);
})->where('any', '.*');


