<?php
$namespace = 'App\Http\Controllers\Api';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UsersController;
use App\Http\Controllers\api\clientController;
use App\Http\Controllers\api\productController;
use App\Http\Controllers\api\productBudgetController;
use App\Http\Controllers\api\budgetPaymentController;
use App\Http\Controllers\api\financialStatisticsController;
use App\Http\Controllers\api\budgetController;
use App\Http\Controllers\api\projectTypeController;
use App\Http\Controllers\api\projectController;

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
        Route::post('registerClient', [clientController::class, 'registerClient']);
        Route::post('updateClient/{id}', [clientController::class, 'updateClient']);
        Route::get('listAllClients/{pages}', [clientController::class, 'listAllClients']);
        Route::get('listClient/{id}', [clientController::class, 'listClient']);
        Route::delete('delete/{id}',[clientController::class, 'deleteClient']);
        Route::get('searchClients', [clientController::class, 'searchClients']);

        //Routes product
        // Route::post('registerProduct', [productController::class, 'registerProduct']);
        // Route::post('updateProduct/{id}', [productController::class, 'updateProduct']);
        // Route::get('listAllProducts/{budget_id}', [productController::class, 'listAllProducts']);
        // Route::get('listProduct/{id}', [productController::class, 'listProduct']);
        // Route::delete('deleteProduct/{id}',[productController::class, 'deleteProduct']);
        
        //Routes users
        Route::get('listAllUsers', [UsersController::class, 'listAllUsers']);
        Route::get('listUser/{id}', [UsersController::class, 'listUser']);
        Route::delete('deleteUser/{id}', [UsersController::class, 'deleteUser']);
        
        //Routes budgets
        Route::post('registerBudget', [budgetController::class, 'registerBudget']);
        Route::post('updateBudget/{id}', [budgetController::class, 'updateBudget']);
        Route::put('updateBudgetDeadline/{id}', [budgetController::class, 'updateBudgetDeadline']);
        Route::put('updateBudgetStatus/{id}', [budgetController::class, 'updateBudgetStatus']);
        Route::put('updateBudgetDescription/{id}', [budgetController::class, 'updateBudgetDescription']);
        Route::put('updateBudgetHired/{id}', [budgetController::class, 'updateBudgetHired']);
        Route::get('listAllBudget', [budgetController::class, 'listAllBudget']);
        Route::get('listBudget/{id}', [budgetController::class, 'listBudget']);
        Route::get('listBudgetsAllPaginate/{pages}', [budgetController::class, 'listBudgetsAllPaginate']);
        Route::delete('deleteBudget/{id}',[budgetController::class, 'deleteBudget']);
        
        //Routes product Budget
        Route::post('registerProductBudget', [productBudgetController::class, 'registerProduct']);
        Route::post('updateProductBudget/{id}', [productBudgetController::class, 'updateProductBudget']);
        Route::get('listAllProductsBudgets/{budget_id}', [productBudgetController::class, 'listAllProductsBudgets']);
        Route::get('listProductBudget/{id}', [productBudgetController::class, 'listProduct']);
        Route::get('listProductBudgetId/{budget_id}', [productBudgetController::class, 'listProductBudgetId']);
        Route::delete('deleteProductBudget/{id}',[productBudgetController::class, 'deleteProduct']);
        
        //Routes Budget Payment
        Route::post('registerBudgetPayment', [budgetPaymentController::class, 'registerBudgetPayment']);
        Route::get('listBudgetPaymentBudgetId/{budget_id}', [budgetPaymentController::class, 'listBudgetPaymentBudgetId']);
        Route::delete('deleteBudgetPayment/{id}', [budgetPaymentController::class, 'deleteBudgetPayment']);

        // Routes Dashboard
        Route::get('amountReceived', [financialStatisticsController::class, 'amountReceived']);
       
        Route::get('/financialSummary', [financialStatisticsController::class, 'getFinancialSummary']);

        Route::get('/earnings', [financialStatisticsController::class, 'getEarnings']);


        //ProjectType
        Route::get('listProjectTypeAll', [projectTypeController::class, 'listProjectTypeAll']);
        Route::post('registerCategory', [projectTypeController::class, 'registerCategory']);

        Route::get('listProjectByType', [projectController::class, 'listProjectByType']);
        Route::post('createProject', [projectController::class, 'createProject']);
        Route::get('listProjectById/{id}', [projectController::class, 'listProjectById']);
       
        
    });
    
   
});