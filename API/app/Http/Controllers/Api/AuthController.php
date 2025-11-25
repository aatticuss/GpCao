<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\Controller;

class AuthController extends Controller
{
    // Registro de usuário via API
    public function register(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'nome_usuario' => 'required|string|max:255|unique:users,nome_usuario',
            'email' => 'required|string|email|unique:users,email',
            'senha' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'nome' => $request->nome,
            'nome_usuario' => $request->nome_usuario,
            'email' => $request->email,
            'senha' => Hash::make($request->senha),
        ]);

        // Cria token pelo Sanctum
        $token = $user->createToken('MeuAppToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // Login de usuário via API
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'senha' => 'required|string',
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nome_usuario';

        $user = User::where($field, $request->login)->first();

        if (!$user || !Hash::check($request->senha, $user->senha)) {
            return response()->json([
                'error' => 'Credenciais inválidas.'
            ], 401);
        }

        $token = $user->createToken('MeuAppToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    // Logout tira o token atual
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.'
        ], 200);
    }
}
