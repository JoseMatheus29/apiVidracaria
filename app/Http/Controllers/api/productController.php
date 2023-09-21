<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\productBudget;

class productController extends Controller
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
}
