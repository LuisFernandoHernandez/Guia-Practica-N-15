<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $datos = $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required'],
            'dispositivo' => ['required', 'max:100'],
        ]);

        $user = User::where('email', $datos['email'])->first();

        if (! $user || ! Hash::check($datos['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales no coinciden.',
            ]);
        }

        $token = $user->createToken($datos['dispositivo']);

        return response()->json([
            'token' => $token->plainTextToken,
            'tipo'  => 'Bearer',
        ], 201);
    }

    public function yo(Request $request)
    {
        return $request->user()->only('id', 'name', 'email');
    }

    public function logout(Request $request)
    {
        // Solo el token con el que se hizo esta petición
        $request->user()->currentAccessToken()->delete();

        return response()->noContent(); // 204
    }

    // ===== Reto: dispositivos con sesión abierta =====

    public function tokens(Request $request)
    {
        return $request->user()->tokens->map(fn($t) => [
            'id'          => $t->id,
            'dispositivo' => $t->name,
            'creado'      => $t->created_at?->toIso8601String(),
            'ultimo_uso'  => $t->last_used_at?->toIso8601String(),
        ]);
    }

    public function cerrarDispositivo(Request $request, int $id)
    {
        // Se busca DENTRO de los tokens del usuario: nunca borra tokens ajenos
        $request->user()->tokens()->where('id', $id)->firstOrFail()->delete();

        return response()->noContent();
    }
}
