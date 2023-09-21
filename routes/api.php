<?php
$namespace = 'App\Http\Controllers\Api';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UsersController;
use App\Http\Controllers\api\clienteController;
use App\Http\Controllers\api\productController;
use App\Models\productBudget;

Route::namespace('Api') -> group(function(){
    //Routes Login
    Route::post('registerUser',[UsersController::class, 'register']);
    Route::get('loginUser',[UsersController::class, 'login']);
    Route::get('logoutUser',[UsersController::class, 'logout']);
    Route::post('sendEmailCodeUser',[UsersController::class, 'sendEmailCode']);
    Route::post('verifyCodePasswordUser',[UsersController::class, 'verifyCodePassword']);
    Route::post('replacePassword',[UsersController::class, 'replacePassword']);


    //Routes clients 
    Route::post('registerClient', [clienteController::class, 'registerClient']);
    Route::get('listAllClients', [clienteController::class, 'listAllClients']);
    Route::get('listClient/{id}', [clienteController::class, 'listClient']);
    Route::delete('delete/{id}',[clienteController::class, 'deleteClient']);


    //Routes product
    Route::post('registerProduct', [productBudget::class, 'registerProduct']);
});