<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\budget;
use App\Models\client;
class budgetController extends Controller
{
    public function registerBudget(Request $request){
        try{
            $budget = new budget();
            $budget->token = $request->token;
            $budget->status = $request->status;
            $budget->amount = $request->amount;
            $budget->created_at = $request->created_at;
            $budget->adress = $request->adress;
            $budget->city = $request->city;
            $budget->description_payment = $request->description_payment;
            
            $users = client::all();
            foreach($users as $user){
                if ($user['id'] == $request->client_id){
                    $budget->client_id = $request->client_id;
                    $budget->save();
                    return ['status' => 'ok'];
                }else{
                    return ['status' => 'erro', 'details' => "cliente não encontrado"];

                }
            }
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    public function listAllBudget(Request $request){
        try{
            $budget = budget::all();
            return $budget;
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
}
