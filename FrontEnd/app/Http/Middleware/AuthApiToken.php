<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthApiToken {
    
    public function handle($request, Closure $next) {

        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader) {
            return response()->json(['message' => 'Token não fornecido'], 401);
        }

        if (preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            $token = $matches[1];
        } else {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Token inválido'], 401);
        }

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
