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
        try{
            $emailUser = DB::select('SELECT * FROM users WHERE email = ?', [$request -> email]);
            if(sizeof($emailUser) != 0){
                $recoveryCode = random_int(10000, 99999); 
                DB::update('UPDATE users SET verifyCode = ? WHERE email = ?', [$recoveryCode, $request->email]);
                $email = new PasswordResetEmail($recoveryCode);
                Mail::to($request->email)->send($email);
                return $recoveryCode;
            }else{
                return "Email não registrado no banco";
            }
            
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    public function verifyCodePassword(Request $request){
        /*Retorna apenas true ou false caso o codigo seja validado ou nao*/
        try{
            $recoveryCodeDb = DB::select('SELECT * FROM users WHERE verifyCode = ?'. [$request -> codigo]);
            if (sizeof($recoveryCodeDb)){
                return true;
            }else{
                return false;
            }
        }
        catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    public function newPassword(Request $request){
        try{
            $passwordsDb = DB::select('SELECT senhas FROM users Where username ?', [$request -> username]);
            $passwordRecevied = $request->senha;
            foreach($passwordsDb as $passwordUser){
                if(password_verify($passwordRecevied,$passwordUser)){
                    DB::update('UPDATE users SET senha = ? Where usarname = ? ', [$request -> senha, $request -> usarname]);
                    return "Senha atualizada";
                }else{
                    return 'Senha não confere';
                }
            }
           
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    
}