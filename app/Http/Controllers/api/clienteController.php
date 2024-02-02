<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\client;
use Illuminate\Support\Facades\Auth;


class clienteController extends Controller
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
            $client->adress = $request->adress;
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
            $client->address = $request->adress;
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
            $client = client::find($id);
            $client->delete();
            return ['status' => 'ok'];

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
}
