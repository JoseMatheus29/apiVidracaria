<?php
$namespace = 'App\Http\Controllers\Api';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UsersController;


Route::namespace('Api') -> group(function(){
    //Rotas Login
    Route::post('register',[UsersController::class, 'register']);
    Route::get('login',[UsersController::class, 'login']);
    Route::get('logout',[UsersController::class, 'logout']);

});