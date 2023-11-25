<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class loginProtectedRoute extends BaseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch(\Exception $erro){
            if($erro instanceof TokenInvalidException){
                return response(['Token invalido'],403);
            }
           else if($erro instanceof TokenExpiredException){
            return response(['Token expirado'],400);
        }
            else{
                return response(['Token não encotrado'],401);
            }
        }
        return $next($request);
    }
}
