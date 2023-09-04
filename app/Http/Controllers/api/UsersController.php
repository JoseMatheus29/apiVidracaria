<?php

namespace App\Http\Controllers\Api;
use App\Models\user;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function register(Request $request){
        try{
            $user = new user();
            $user->username = $request->username;
            $user->email = $request->email;
            $passwordCrip = password_hash($request->password, PASSWORD_DEFAULT);
            $user->password = $passwordCrip;

            $user->save();
            return ['status' => 'ok'];
        }
        catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }   
    public function login(Request $request){
            try{
                $users = DB::select('SELECT * FROM users');
                foreach($users as $user){
                    if($user->username == $request->username){
                        $senha = $user->password;
                        $passwordRecevied = $request->password;

                        if(password_verify($passwordRecevied,$senha)){
                            $user -> login = 1;
                            return ['status' => 'ok'];
                        }else{
                            return ['status' => 'erro', 'details' => 'senha não confere'];
                        }
                    }
                }
                
            }catch(\Exception $erro){
                return ['status' => 'erro', 'details' => $erro];
            }

    }
   

     
}
