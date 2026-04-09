<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // ⚡ Très important : n'oubliez pas d'importer Hash

class UtilisateurController extends Controller
{
    // ... vos autres méthodes (index, show, store, etc.) ...

    public function login(Request $request)
    {
        // 1. Validation des champs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Recherche de l'utilisateur
        $user = Utilisateur::where('email', $request->email)->first();

        // 3. Vérification de l'utilisateur et du mot de passe
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Les identifiants sont incorrects.'
            ], 401);
        }

        // 4. Vérification stricte du rôle (Seul l'Admin peut se connecter ici)
        if ($user->role !== 'ADMIN_SYSTEME') {
            return response()->json([
                'message' => 'Accès refusé. Réservé aux administrateurs.'
            ], 403);
        }

        // 5. Mettre à jour la date de dernière connexion (puisque vous avez ce champ !)
        $user->update([
            'derniereConnexion' => now()
        ]);

        // 6. Génération du token Sanctum
        // Optionnel : on peut supprimer les anciens tokens avec $user->tokens()->delete();
        $token = $user->createToken('admin_token')->plainTextToken;

        // 7. Réponse avec le token
        return response()->json([
            'message' => 'Connexion réussie',
            'utilisateur' => $user,
            'token' => $token
        ], 200);
    }
}