<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // ⚡ Très important : n'oubliez pas d'importer Hash

use Illuminate\Support\Facades\Auth;
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
    public function store(Request $request) {
    $user = Auth::user();

    if (!$user || $user->role !== 'ADMIN_SYSTEME') {
        return response()->json(['message' => 'Seul le Super Admin peut créer des utilisateurs.'], 403);
    }

    $data = $request->validate([
        'nom'=>'required|string',
        'prenom'=>'required|string',
        'username'=>'required|string|unique:utilisateurs',
        'email'=>'required|email|unique:utilisateurs',
        'password'=>'required|string',
        'telephone'=>'nullable|string',
        'role'=>'required|in:RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT'
    ]);

    $data['password'] = bcrypt($data['password']);
    $newUser = Utilisateur::create($data);

    return response()->json($newUser, 201);
}

    public function update(Request $request, $id) {
        $user = Utilisateur::findOrFail($id);
        $user->update($request->all());
        return response()->json($user);
    }

        // 7. Réponse avec le token
        return response()->json([
            'message' => 'Connexion réussie',
            'utilisateur' => $user,
            'token' => $token
        ], 200);
    }
}