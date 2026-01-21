<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registro de nuevo usuario
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Inicio de sesión
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            // Mensajes personalizados
            'email.required' => 'El campo email es obligatorio',
            'email.email' => 'El email debe ser una dirección válida',
            'password.required' => 'El campo password es obligatorio',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Las credenciales son incorrectas'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Cerrar sesión (revocar token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente',
        ]);
    }

    /**
     * Obtener usuario autenticado
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Obtener perfil completo con estadísticas
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        // Estadísticas del usuario
        $stats = [
            'total_favorites' => $user->favorites()->count(),
            'total_watchlist' => $user->watchlist()->count(),
            'movies_watchlist' => $user->watchlist()->where('media_type', 'movie')->count(),
            'tv_watchlist' => $user->watchlist()->where('media_type', 'tv')->count(),
            'completed' => $user->watchlist()->where('status', 'completed')->count(),
            'watching' => $user->watchlist()->where('status', 'watching')->count(),
            'plan_to_watch' => $user->watchlist()->where('status', 'plan_to_watch')->count(),
            'total_searches' => $user->searchHistory()->count(),
        ];

        return response()->json([
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    /**
     * Actualizar perfil
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $request->user()->id,
        ]);

        $user = $request->user();
        $user->update($request->only(['name', 'email']));

        return response()->json([
            'message' => 'Perfil actualizado exitosamente',
            'user' => $user,
        ]);
    }

    /**
     * Cambiar contraseña
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        // Verificar contraseña actual
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'La contraseña actual es incorrecta',
            ], 422);
        }

        // Actualizar contraseña
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada exitosamente',
        ]);
    }

    /**
     * Eliminar cuenta
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        // Verificar contraseña
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Contraseña incorrecta',
            ], 422);
        }

        // Eliminar todos los tokens
        $user->tokens()->delete();

        // Eliminar cuenta (cascade eliminará favoritos, watchlist, búsquedas)
        $user->delete();

        return response()->json([
            'message' => 'Cuenta eliminada exitosamente',
        ]);
    }
}
