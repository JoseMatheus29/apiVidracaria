<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\budget;
use App\Models\productBudget;
use App\Models\project;

class productBudgetController extends Controller
{
    public function registerProduct(Request $request){
        try{
            $product = new productBudget();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->quantity = $request->quantity;
            $product->value = $request->value;
            $product->project_id = $request->project_id;
            $product->type = $request->type;
            $product->height = $request->height;
            $product->width = $request->width;
            $product->location = $request->location;
            $product->glasses = $request->glasses;
            $product->sheets = $request->sheets;
            $product->amount =  $request->value * $request->quantity;
            $verifyId = $request->validate(['budget_id' => 'exists:budgets,id',]);
            if($verifyId){
                $product->budget_id = $request->budget_id;
            }
            $product->save();
            return ['status' => 'ok'];

             // Encontre o orçamento associado a este produto
             $budget = Budget::find($product->budget_id);
    
             // Verifique se o pagamento do orçamento precisa ser atualizado
             if ($budget->amount <= $budget->paid_amount) {
                $budget->payed = 1;
                $budget->payed_at = now();
             } else {
                $budget->payed = 0;
                $budget->payed_at = null;
             }

             $budget->save();

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }

    public function updateProductBudget(Request $request, $id){
        try {
            $product = productBudget::find($id);
            $product->name = $request->name;
            $product->description = $request->description;
            $product->quantity = $request->quantity;
            $product->value = $request->value;
            $product->project_id = $request->project_id;
            $product->type = $request->type;
            $product->height = $request->height;
            $product->width = $request->width;
            $product->location = $request->location;
            $product->glasses = $request->glasses;
            $product->sheets = $request->sheets;
            $product->amount =  $request->value * $request->quantity;
            $verifyId = $request->validate(['budget_id' => 'exists:budgets,id']);
    
            if ($verifyId) {
                $product->budget_id = $request->budget_id;
            }
            
            $product->save();
    
            // Encontre o orçamento associado a este produto
            $budget = Budget::find($product->budget_id);
    
            // Verifique se o pagamento do orçamento precisa ser atualizado
            if ($budget->amount <= $budget->paid_amount) {
                $budget->payed = 1;
                $budget->payed_at = now();
                
             } else {
                $budget->payed = 0;
                $budget->payed_at = null;
             }
    
            $budget->save();
    
            return ['status' => 'ok'];
        } catch(\Exception $erro) {
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    
    public function listAllProductsBudgets($budget_id)  {
        try{
            $product = productBudget::where('budget_id', '=', $budget_id)->get();
            

            // Obter os projetos
            $projects = project::select('id', 'image_url')->get();

            // Adiciona o nome do cliente a cada item do orçamento
            foreach ($product as $item) {
                foreach ($projects as $project) {
                    if ($item->project_id == $project->id) {
                        $item->image_url = $project->image_url;
                        break;
                    }
                }
            }

            return $product; 

        } catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    


    public function listProduct($id){
        try{
            $product = productBudget::find ($id);
            return $product;

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }

    public function listProductBudgetId($budget_id){
        try{
            $product = productBudget::where('budget_id', '=', $budget_id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
            

            // Obter os projetos
            $projects = project::select('id','name', 'image_url')->get();

            // Adiciona o nome do cliente a cada item do orçamento
            foreach ($product as $item) {
                foreach ($projects as $project) {
                    if ($item->project_id == $project->id) {
                        $item->image_url = $project->image_url;
                        $item->project_name = $project->name;
                        break;
                    }
                }
            }

            return $product; 

        } catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }

    public function deleteProduct($id){
        try{
            $product = productBudget::find($id);
            

             // Encontre o orçamento associado a este produto
             $budget = Budget::find($product->budget_id);

             $product->delete();
    
             // Verifique se o pagamento do orçamento precisa ser atualizado
             if ($budget->amount <= $budget->paid_amount) {
                $budget->payed = 1;
                $budget->payed_at = now();
               
             } else {
                $budget->payed = 0;
                $budget->payed_at = null;
               
             }
             $budget->save();
            

            return ['status' => 'ok'];

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
}
