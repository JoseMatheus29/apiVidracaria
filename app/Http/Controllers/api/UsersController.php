<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\user;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetEmail;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Group;
use Tymon\JWTAuth\Contracts\JWTSubject ;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsersController extends Controller implements JWTSubject
{
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function register(Request $request){
        try{
            $user = new user();
            $user->name = $request->name;
            $user->avatar_url = $request->avatar_url;
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
    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function login(Request $request){
        $username = $request->username;
        $credentials = $request->only(['username', 'password']);
        $user = user::select('name', 'avatar_url', 'username')->where('username', '=', $username)->get();
        if (! $token = Auth(guard:'api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $tokenPass = $this->respondWithToken($token);
        return ['token' => $tokenPass,'name' =>  $user[0]['name'], 'avatar' =>  $user[0]['avatar_url'], 'username' =>  $user[0]['username']];    


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
    public function replacePassword(Request $request){
        try{
            $passwordsDb = DB::select('SELECT password FROM users Where username = ?', [$request -> username]);
            $passwordOld = password_hash($request->passwordOldUser, PASSWORD_DEFAULT);
            $passawordNew = $request->passawordNewUser;
            if (!empty($passwordsDb)) {
                if($passwordOld == $passwordsDb[0]->password){
                    DB::update('UPDATE users SET password = ? Where username = ? ', [$passawordNew, $request -> usarname]);
                    return "Senha atualizada";
                }else{
                    
                    return 'Senha não confere';
                }
            }else{
                return ['status' => 'erro', 'details' => "Usuario não encontrado!"];
            }
           
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    public function newPassword(Request $request){
        try{
            $passawordNew = $request->passawordNewUser;
            DB::update('UPDATE users SET password = ? WHERE email = ?', [$passawordNew, $request->email]);
            return ['status' => 'ok'];

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }

    public function usernameGerator(Request $request){
        try{
            $name = $request->name;
            $lastName = $request->lastName;
            $firstTwoLetters = substr($lastName, 0, 1);
            $newName = $name . $firstTwoLetters;
            return $newName;
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }

    public function listAllUsers()  {
        try{
            if (Auth::check()) {
                $user = user::all();
                return $user;
            }   else {
                return ['status' => 'erro', 'details' => 'Usuário não autenticado'];
            }
        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    public function listUser($id){
        try{
            $user = user::find ($id);
            return $user;

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }
    public function deleteUser($id){
        try{
            $user = user::find($id);
            $user->delete();
            return ['status' => 'ok'];

        }catch(\Exception $erro){
            return ['status' => 'erro', 'details' => $erro];
        }
    }

        /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}