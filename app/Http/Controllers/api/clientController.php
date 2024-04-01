<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\client;
use Illuminate\Support\Facades\Auth;
use App\Models\budget;
use App\Models\product;
use App\Models\BudgetPayment;
use App\Models\productBudget;


class clientController extends Controller
{
    public function registerClient(Request $request){
        try{
            $client = new client();
            $client->name = $request->name;
            $client->cpf = $request->cpf;
            $client->city = $request->city;
            $client->birthday = $request->birthday;
            $client->email = $request->email;
            $client->tel = $request->tel;
            $client->address = $request->address;
            $client->save();
            return ['status' => 'ok'];
        }
        catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    public function updateClient(Request $request, $id){
        try{
            $client =  client::find($id);
            $client->name = $request->name;
            $client->cpf = $request->cpf;
            $client->city = $request->city;
            $client->birthday = $request->birthday;
            $client->email = $request->email;
            $client->tel = $request->tel;
            $client->address = $request->address;
            $client->save();
            return ['status' => 'ok'];
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    public function listAllClients($page)  {
        try{
            if (Auth::check()) {
                if($page != 0){
                    
                    $client = client::orderBy('name')->paginate(10, ['*'], 'page', $page);
                    return $client;
                }else{
                    $client = client::all();
                    return $client;
                }
            }   else {
                return ['status' => 'erro', 'details' => 'Usuário não autenticado'];
            }

        }catch(\Exception $erro){
            
            return ['status' => 'erro', 'details' => $erro];

        }
    }


    public function searchClients(Request $request) {
        try {
            if (Auth::check()) {
                $query = $request->query('search');
    
                $clients = Client::where('name', 'LIKE', "%$query%")
                                ->orderBy('name')
                                ->get();
    
                return $clients;
            } else {
                return ['status' => 'erro', 'details' => 'Usuário não autenticado'];
            }
        } catch (\Exception $error) {
            return ['status' => 'erro', 'details' => $error];
        }
    }
    
    


    public function listClient( $id){
        try{
            $client = client::find ($id);
            return $client;

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }

    public function deleteClient($id){
        try{
            $budget_payments = BudgetPayment::where('client_id', $id);
            $budget_payments->delete();

            $budgets = Budget::where('client_id', $id)->get();

            foreach ($budgets as $budget) {
                $product = productBudget::where('budget_id', $budget->id);
                $product->delete();
            }

            $budget->delete();
            
            $client = client::find($id);
            $client->delete();
            return ['status' => 'ok'];

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
}
