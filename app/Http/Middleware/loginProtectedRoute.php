<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class loginProtectedRoute extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch(\Exception $erro){
            if($erro instanceof TokenInvalidException){
                return ['status' => 'Token invalido', 'details' => $erro];
            }
           else if($erro instanceof TokenExpiredException){
                return ['status' => 'Token expirado', 'details' => $erro];
            }
            else{
                return ['status' => 'Token de autorização não encontrado', 'details' => $erro];
            }
        }
        return $next($request);
    }
}
