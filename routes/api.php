<?php
$namespace = 'App\Http\Controllers\Api';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UsersController;
use App\Http\Controllers\api\clienteController;
use App\Http\Controllers\api\productController;
use App\Http\Controllers\api\productBudgetController;

Route::namespace('Api') -> group(function(){
    //Routes Login
    Route::post('registerUser',[UsersController::class, 'register']);
    Route::post('loginUser',[UsersController::class, 'login']);
    Route::get('logoutUser',[UsersController::class, 'logout']);
    Route::post('usernameGerator', [UsersController::class, 'usernameGerator']);

    //Routes replacePassword
    Route::post('sendEmailCodeUser',[UsersController::class, 'sendEmailCode']);
    Route::post('verifyCodePasswordUser',[UsersController::class, 'verifyCodePassword']);
    Route::post('replacePassword',[UsersController::class, 'replacePassword']);
    Route::post('newPassword',[UsersController::class, 'newPassword']);


    Route::group(['middleware' => ['apiProtect']], function () {
        //Crud clients 
        Route::post('registerClient', [clienteController::class, 'registerClient']);
        Route::get('listAllClients', [clienteController::class, 'listAllClients']);
        Route::get('listClient/{id}', [clienteController::class, 'listClient']);
        Route::delete('delete/{id}',[clienteController::class, 'deleteClient']);
        //Routes product
        Route::post('registerProduct', [productController::class, 'registerProduct']);
        Route::get('listAllProducts', [productController::class, 'listAllProducts']);
        Route::get('listProduct/{id}', [productController::class, 'listProduct']);
        Route::delete('deleteProduct/{id}',[productController::class, 'deleteProduct']);
        //Routes users
        Route::get('listAllUsers', [UsersController::class, 'listAllUsers']);
        Route::get('listUser/{id}', [UsersController::class, 'listUser']);
        Route::delete('deleteUser/{id}', [UsersController::class, 'deleteUser']);
        //Routes product Budget
        Route::post('registerProductBudget', [productBudgetController::class, 'registerProduct']);
        Route::get('listAllProductBudget', [productBudgetController::class, 'listAllProducts']);
        Route::get('listProductBudget/{id}', [productBudgetController::class, 'listProduct']);
        Route::delete('deleteProductBudget/{id}',[productBudgetController::class, 'deleteProduct']);




    });





});