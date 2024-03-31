<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BudgetPayment;
use App\Models\budget;
use App\Models\client;

class budgetPaymentController extends Controller
{
    public function registerBudgetPayment(Request $request){
        try{
             // Verificar se o cliente existe
            $client = Client::find($request->client_id);
            if (!$client) {
                throw new \Exception('Cliente não encontrado.');
            }

            // Verificar se o orçamento existe
            $budget = Budget::find($request->budget_id);
            if (!$budget) {
                throw new \Exception('Orçamento não encontrado.');
            }

            // Verificar se o pagamento excede o valor do orçamento
            if ($budget->paid_amount + $request->value > $budget->amount) {
                throw new \Exception('O pagamento excede o valor do orçamento.');
            }

            $budget_payment = new BudgetPayment();
            
            $budget_payment->description = $request->description;
            $budget_payment->value = $request->value;
            $budget_payment->type = $request->type;
            $budget_payment->budget_id = $request->budget_id;
            $budget_payment->client_id = $request->client_id;
            $budget_payment->created_at = $request->created_at;
                
            $budget_payment->save();

            if ($budget->paid_amount + $request->value == $budget->amount) {
                $budget->payed = 1;
                $budget->save();
            }
            
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
    
            // Obter o orçamento
            $budget = Budget::find($budget_id);
    
            // Calcular o amountFinal (valor restante)
            $remaining_amount = $budget->amount - $budget->paid_amount;
    
            // Adicionar amountFinal aos dados do orçamento e retornar
            $budgetData = [
                'data' => $budget_payments,
                'paid_amount' => $budget->paid_amount,
                'remaining_amount' => $remaining_amount 
            ];
    
            return $budgetData;
        } catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro->getMessage()];
        }
    }
    
    
    
}
