<?php

namespace App\Http\Controllers\Api;
use App\Models\user;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function register(Request $request){
        try{
            $user = new user();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = $request->password;

            $user->save();
            return ['status' => 'ok'];
        }
        catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }

    }
}
