<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\client;


class clienteController extends Controller
{
    public function register(Request $request){
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
}
