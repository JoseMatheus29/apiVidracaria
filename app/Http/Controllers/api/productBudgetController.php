<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\productBudget;

class productBudgetController extends Controller
{
    public function registerProduct(Request $request){
        try{
            $product = new productBudget();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->quantity = $request->quantity;
            $product->price = $request->price;
            $product->save();
            return ['status' => 'ok'];

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    public function listAllProducts()  {
        try{
            $product = productBudget::all();
            return $product;
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    public function listProduct( $id){
        try{
            $product = productBudget::find ($id);
            return $product;

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }

    public function deleteProduct($id){
        try{
            $product = productBudget::find($id);
            $product->delete();
            return ['status' => 'ok'];

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
}
