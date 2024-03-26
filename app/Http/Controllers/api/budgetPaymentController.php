<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BudgetPayment;
use App\Models\budget;

class budgetPaymentController extends Controller
{
    public function registerBudgetPayment(Request $request){
        try{
            $budget_payment = new BudgetPayment();
            
            $budget_payment->description = $request->description;
            $budget_payment->value = $request->value;
            $budget_payment->type = $request->type;
            $budget_payment->budget_id = $request->budget_id;
            $budget_payment->client_id = $request->client_id;
            $budget_payment->created_at = $request->created_at;
                
            $budget_payment->save();
            
            return ['status' => 'ok'];
        }
        catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro->getMessage()];
        }
    }

    public function listBudgetPaymentBudgetId($budget_id){
        try{
            // Obter todos os pagamentos do orçamento
            $budget_payments = BudgetPayment::where('budget_id', $budget_id)
                ->orderBy('created_at', 'asc')
                ->get();

            $budget = budget::find($budget_id);
    
            // Calcular o amountFinal
            
            $amountFinale = $budget->amount;

            foreach ($budget_payments as $payment) {
                $amountFinale -= $payment->value;
            
            }
    
            // Adicionar amountFinale aos dados do orçamento e retornar
            $budgetData = [
                'data' => $budget_payments,
                'amountFinale' => $amountFinale
            ];
    
            return $budgetData;
        } catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro->getMessage()];
        }
    }
    
    
}
