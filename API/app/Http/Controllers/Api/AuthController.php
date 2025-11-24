<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
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

        // Cria token de autenticação pessoal (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuário registrado com sucesso.',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

    // Login de usuário via API
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'senha' => 'required|string',
        ]);

        // Define se o login é email ou nome de usuário
        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nome_usuario';

        $user = User::where($field, $request->login)->first();

        if (!$user || !Hash::check($request->senha, $user->senha)) {
            return response()->json([
                'message' => 'As credenciais fornecidas não são válidas.'
            ], 401);
        }

        // Cria token de acesso pessoal
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso.',
            'user'    => $user,
            'token'   => $token
        ], 200);
    }

    // Logout (revoga token atual)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.'
        ], 200);
    }
}
