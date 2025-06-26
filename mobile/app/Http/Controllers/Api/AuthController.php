<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
public function login(Request $request)
{
    try {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciais inválidas',
                'success' => false
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login realizado com sucesso',
            'success' => true,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erro no servidor: ' . $e->getMessage(),
            'success' => false
        ], 500);
    }
}
}