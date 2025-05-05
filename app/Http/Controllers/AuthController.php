<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Datos incorrectos'], 401);
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        return response()
        ->json([
            'message'=>'login exitoso',
            'usuario' => $user->name,
            'email' => $user->email,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
        //return $user->createToken($request->device_name)->plainTextToken;
    }

    public function logout() {
        dd(auth()->user());
        //auth()->user()->tokens()->where('id', $tokenId)->delete();
        auth()->user()->tokens()->delete();
        return ['message' => 'has cerrdo sesion en todos tus dispositivos'];
    }
}
