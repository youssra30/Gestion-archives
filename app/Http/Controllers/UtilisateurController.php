<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = Utilisateur::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Les identifiants sont incorrects.'
            ], 401);
        }

        if (!in_array($user->role, ['SUPER_ADMIN', 'ADMIN_SYSTEME'])) {
            return response()->json([
                'message' => 'Accès refusé.'
            ], 403);
        }

        $user->update([
            'derniereConnexion' => now()
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'utilisateur' => $user,
            'token' => $token
        ], 200);
    }
     
    public function store(Request $request)
    {
        $request->validate([
         'username' => 'required|unique:utilisateurs',
         'nom' => 'required',
         'prenom' => 'required',
         'email' => 'required|email|unique:utilisateurs',
         'password' => 'required|min:6',
         'role' => 'required|in:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL'
        ]);

       $currentUser = $request->user();

       
        if (!$currentUser) {
            return response()->json([
                'message' => 'Non authentifié'
            ], 401);
        }

     
        if ($currentUser->role === 'SUPER_ADMIN') {
            if ($request->role !== 'ADMIN_SYSTEME') {
                return response()->json([
                    'message' => 'SuperAdmin peut créer Admin!!'
                ], 403);
            }
        }

        elseif ($currentUser->role === 'ADMIN_SYSTEME') {
            if (!in_array($request->role, ['RESPONSABLE_ARCHIVES', 'AGENT_ACCUEIL'])) {
                return response()->json([
                    'message' => 'Admin ne peut créer que Responsable ou Agent.'
                ], 403);
            }
        }

        else {
            return response()->json([
                'message' => 'Accès interdit'
            ], 403);
        }

       $user = Utilisateur::create([
         'username' => $request->username,
         'nom' => $request->nom,
         'prenom' => $request->prenom,
         'email' => $request->email,
         'password' => Hash::make($request->password),
         'role' => $request->role
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user
        ]);
    }
}