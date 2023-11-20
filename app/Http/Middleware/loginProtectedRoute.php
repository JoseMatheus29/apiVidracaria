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
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch(\Exception $erro){
            if($erro instanceof TokenInvalidException){
                return new Response('Token invalido'. "<br>"  . $erro);
            }
           else if($erro instanceof TokenExpiredException){
                return new Response('Token expirado' . "<br>"  . $erro);
            }
            else{
                return new Response('Token de autorização não encontrado' . "<br>"  . $erro);
            }
        }
        return $next($request);
    }
}
