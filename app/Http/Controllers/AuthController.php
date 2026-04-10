<?php

namespace App\Http\Controllers;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  

    public function login(Request $request)
    {
        $user = Utilisateur::where('email', $request->email)->first();

       if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;
    
    return response()->json([
        'message' => 'Login success',
        'user' => $user ,
        'token' => $token
    ]);
}
}

