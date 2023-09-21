<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\client;


class clienteController extends Controller
{
    public function registerClient(Request $request){
        try{
            $client = new client();
            $client->name = $request->name;
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
    public function listAllClients()  {
        try{

            $client = client::all();
            return $client;

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
