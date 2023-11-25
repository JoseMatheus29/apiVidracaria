<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\product;

class productController extends Controller
{
    public function registerProduct(Request $request){
        try{
            $product = new product();
            $product->name = $request->name;
            $product->category = $request->category;
            $product->sheets = $request->sheets;
            $product->save();
            return ['status' => 'ok'];

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    public function listAllProducts()  {
        try{
            $product = product::all();
            return $product;
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    public function listProduct( $id){
        try{
            $product = product::find ($id);
            return $product;

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }

    public function deleteProduct($id){
        try{
            $product = product::find($id);
            $product->delete();
            return ['status' => 'ok'];

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
}
