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
        $budget->address = $request->address;
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
        $budget->address = $request->address;
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
 

    public function listBudget($id){
        try{
            $budget = budget::find ($id);
            return $budget;
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }

    public function listBudgetEspecify($page) {
        try {
            $budget = Budget::select('id', 'created_at', 'amount', 'client_id')
                        ->orderBy('created_at', 'desc') // Ordena por ordem de criação (descendente)
                        ->paginate(10, ['*'], 'page', $page);
            
            $clients = Client::select('id', 'name')->get();
            
            foreach ($budget as $item) {
                foreach ($clients as $client) {
                    if ($item->client_id == $client->id) {
                        $item->client_name = $client->name;
                        break;
                    }
                }
            }
    
            return $budget;
        } catch(\Exception $error) {
            return ['status' => 'error', 'details' => $error];
        }
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
