<?php

namespace App\Http\Controllers\Api;
use App\Models\user;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetEmail;

class UsersController extends Controller
{
    protected $recoveryCode;

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
                            DB::update('UPDATE users SET login = true ');
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
    public function logout(Request $request){
        try{
            $users = DB::select('SELECT * FROM users');
            foreach($users as $user){
                if($user->username == $request->username){
                    $senha = $user->password;
                    $passwordRecevied = $request->password;

                    if(password_verify($passwordRecevied,$senha)){
                        DB::update('UPDATE users SET login = false ');
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
    public function sendEmailCode (Request $request){
        $this -> recoveryCode = random_int(10000, 99999); 
        $email = new PasswordResetEmail($this->recoveryCode);
        Mail::to($request->email)->send($email);
        return $this->recoveryCode;
    }
   
    
}