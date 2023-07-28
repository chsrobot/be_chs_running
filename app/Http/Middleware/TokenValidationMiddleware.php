<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TokenValidationMiddleware
{
    public function handle($request, Closure $next)
    {
       
        $authorizationHeader = $request->header('Authorization');
        
       

        if (empty($authorizationHeader) || !str_starts_with($authorizationHeader, 'Bearer ')) {
            
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $token = substr($authorizationHeader, 7);

        $expiresAt = $request->header('Token-Expires-At');



        if (!$expiresAt || Carbon::parse($expiresAt)->isPast()) {
            error_log($authorizationHeader);
            return response()->json(['error' => 'Token Expired'], 401);
        }


        if (empty($token) || empty($expiresAt)) {
            return response()->json(['error' => 'Invalid token or expiresAt headers'], 401);
        }

        $tokenData = [
            'email' => $request->email,
            'salt' => "H7AdNZtPtduXWaPp",
            'expires_at' => $expiresAt,
        ];

        $generatedToken = json_encode($tokenData);

        error_log($generatedToken);

        if (!Hash::check($generatedToken, $token)) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        if (Hash::check($generatedToken, $token)) {
            return $next($request);
        }

        return $next($request);
    }
}