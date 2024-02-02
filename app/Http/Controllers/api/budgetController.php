<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\budget;
use App\Models\client;
use App\Models\product;
use App\Models\productBudget;

class budgetController extends Controller
{
    public function registerBudget(Request $request){
        $budget = new budget();
        $budget->status = $request->status;
        $budget->created_at = $request->created_at;
        $budget->adress = $request->adress;
        $budget->city = $request->city;
        $budget->description_payment = $request->description_payment;
        $users = client::all();
        foreach($users as $user){
            if ($user['id'] == $request->client_id){
                $budget->client_id = $request->client_id;
            }
        }
        $budget->amount = 0;
        $budget -> save();
        return ['status' => 'ok'];
    }

    public function updateBudget(Request $request, $id){
        $budget =  budget::find($id);
        $budget->status = $request->status;
        $budget->created_at = $request->created_at;
        $budget->adress = $request->adress;
        $budget->city = $request->city;
        $budget->description_payment = $request->description_payment;
        $users = client::all();
        foreach($users as $user){
            if ($user['id'] == $request->client_id){
                $budget->client_id = $request->client_id;
            }
        }
        
        $budget -> save();
        return ['status' => 'ok'];
    }

    public function listAllBudget(Request $request){
        try{
            $budget = budget::all();
            return $budget;
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }

    public function list4(Request $request){
        try{
            $budget = budget::all();
            return $budget;
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }

    public function listBudgetEspecify($id){

            $budget = budget::select('id', 'created_at', 'amount', 'client_id' )->get();
            $client = client::select('id','name')->get();
            for($c = 0; $c < sizeof($budget); $c++){
                for($d = 0; $d < sizeof($client); $d++){
                    if($budget[$c]['client_id'] == $client[$d]['id']){
                        $budget[$c]['client_name'] = $client[$d]['name'];
                    }
                }

            }
            return $budget;

    }
    public function deleteBudget($id){
        try{
            $budget = budget::find ($id);
            $budget -> delete();
            return ['status' => 'ok'];

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
}
