<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\budget;
use App\Models\productBudget;
use App\Models\project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class productBudgetController extends Controller
{
    public function registerProduct(Request $request)
{
    try {

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
        $product->amount = $request->value * $request->quantity;

        // Handle the image upload
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imagePath = $image->store('product_images', 'public');
            $imageUrl = Storage::url($imagePath);
            $imageUrl = str_replace('public/', 'storage/', $imageUrl);
            $fullUrl = url($imageUrl);
            $product->product_image = $fullUrl;
        } else {
            $product->product_image = '';
        }

        // Validate and assign the budget_id
        $verifyId = $request->validate(['budget_id' => 'exists:budgets,id',]);
        if ($verifyId) {
            $product->budget_id = $request->budget_id;
        }

        $product->save();

        // Find the associated budget
        $budget = Budget::find($product->budget_id);

        // Check if the budget payment status needs to be updated
        if ($budget->amount <= $budget->paid_amount) {
            $budget->payed = 1;
            $budget->payed_at = now();
        } else {
            $budget->payed = 0;
            $budget->payed_at = null;
        }

        $budget->save();

        return response()->json(['status' => 'ok', 'product' => $product], 201);

    } catch (\Exception $erro) {
        return response()->json(['status' => 'erro', 'details' => $erro->getMessage()], 500);
    }
}

public function updateProductBudget(Request $request, $id)
{
    try {
        $product = productBudget::find($id);
        
        if (!$product) {
            return response()->json(['status' => 'erro', 'details' => 'Produto não encontrado'], 404);
        }
        
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
        $product->amount = $request->value * $request->quantity;

        // Handle the image upload
        if ($request->remove_image) {
            // Remove the old image if it exists
            if ($product->product_image) {
                $oldImagePath = str_replace(url('/storage/'), 'public/', $product->product_image);
                Storage::delete($oldImagePath);
                $product->product_image = null;
            }
        } 
        
        if ($request->hasFile('product_image')) {
            // // Delete the old image if it exists
            // if ($product->product_image) {
            //     $oldImagePath = str_replace(url('/storage/'), 'public/', $product->product_image);
            //     Storage::delete($oldImagePath);
            // }

            $image = $request->file('product_image');
            $imagePath = $image->store('product_images', 'public');
            $imageUrl = Storage::url($imagePath);
            $imageUrl = str_replace('public/', 'storage/', $imageUrl);
            $fullUrl = url($imageUrl);
            $product->product_image = $fullUrl;
        }

        // Validate and assign the budget_id
        $verifyId = $request->validate(['budget_id' => 'exists:budgets,id']);
        if ($verifyId) {
            $product->budget_id = $request->budget_id;
        }

        $product->save();

        // Find the associated budget
        $budget = Budget::find($product->budget_id);

        // Check if the budget payment status needs to be updated
        if ($budget->amount <= $budget->paid_amount) {
            $budget->payed = 1;
            $budget->payed_at = now();
        } else {
            $budget->payed = 0;
            $budget->payed_at = null;
        }

        $budget->save();

        return response()->json(['status' => 'ok', 'product' => $product], 200);
    } catch (\Exception $erro) {
        return response()->json(['status' => 'erro', 'details' => $erro->getMessage()], 500);
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
