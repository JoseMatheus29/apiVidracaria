<?php
$namespace = 'App\Http\Controllers\Api';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UsersController;
use App\Http\Controllers\api\clienteController;

Route::namespace('Api') -> group(function(){
    //Routes Login
    Route::post('registerUser',[UsersController::class, 'register']);
    Route::get('loginUser',[UsersController::class, 'login']);
    Route::get('logoutUser',[UsersController::class, 'logout']);
    Route::post('sendEmailCodeUser',[UsersController::class, 'sendEmailCode']);
    Route::post('verifyCodePasswordUser',[UsersController::class, 'verifyCodePassword']);
    Route::post('newPasswordUser',[UsersController::class, 'newPassword']);


    //Routes clients 
    Route::post('registerClient', [clienteController::class, 'register']);
    
});