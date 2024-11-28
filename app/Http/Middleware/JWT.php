<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JWT
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = JWTAuth::parseToken();
        } catch (\Exception $e) {
            return response()->json('Authorization token not found', 401);
        }
        
        try {
            if ( !$token || empty($token->authenticate()) )
                response()->json('Unauthorized', 401);
        } catch (\Exception $e) {
            return response()->json('Unauthorized', 401);
        }
       
        return $next($request);
    }
}
