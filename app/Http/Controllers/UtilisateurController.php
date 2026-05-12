<?php

namespace App\Http\Controllers;

use App\Exports\UtilisateursExport;
use App\Imports\UtilisateursImport;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UtilisateurController extends Controller
{
    public function index()
    {
        return response()->json(Utilisateur::all());
    }

    public function show($id)
    {
        return response()->json(Utilisateur::findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:utilisateurs',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:utilisateurs',
            'password' => 'required|min:6',
            'telephone' => 'nullable|string',
            'role' => 'required|in:SUPER_ADMIN,ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL',
        ]);

        $currentUser = $request->user();

        if (!$currentUser) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        if ($currentUser->role === 'SUPER_ADMIN') {
            // Super admin peut créer : SUPER_ADMIN, ADMIN_SYSTEME, RESPONSABLE_ARCHIVES, AGENT_ACCUEIL
            // Les nouveaux super admins auront is_origin = false
        } elseif ($currentUser->role === 'ADMIN_SYSTEME') {
            if (!in_array($request->role, ['RESPONSABLE_ARCHIVES', 'AGENT_ACCUEIL'], true)) {
                return response()->json(['message' => 'Admin peut créer Responsable_Archives ou Agent_Accueil'], 403);
            }
        } else {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        $user = Utilisateur::create([
            'username' => $request->username,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_origin' => false, // Les nouveaux utilisateurs ne sont jamais origin
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $currentUser = $request->user();
        $targetUser = Utilisateur::findOrFail($id);

        // ─── Garde 1 : personne ne peut modifier un utilisateur is_origin (sauf via profil) ───
        if ($targetUser->is_origin && $currentUser->id !== $targetUser->id) {
            return response()->json(['message' => 'Le super administrateur d\'origine ne peut pas être modifié'], 403);
        }

        // ─── Garde 2 : un utilisateur ne peut pas changer son propre rôle via cette route ───
        if ($currentUser->id === (int) $id && $request->has('role') && $request->role !== $currentUser->role) {
            return response()->json(['message' => 'Vous ne pouvez pas modifier votre propre rôle'], 403);
        }

        // ─── Garde 3 : un super admin non-origin ne peut pas modifier un autre super admin ───
        if (!$currentUser->is_origin && $targetUser->role === 'SUPER_ADMIN' && $currentUser->id !== $targetUser->id) {
            return response()->json(['message' => 'Seul le super administrateur d\'origine peut modifier un autre super administrateur'], 403);
        }

        $data = $request->validate([
            'username' => 'sometimes|unique:utilisateurs,username,' . $id,
            'nom' => 'sometimes|string',
            'prenom' => 'sometimes|string',
            'email' => 'sometimes|email|unique:utilisateurs,email,' . $id,
            'telephone' => 'nullable|string',
            'password' => 'nullable|min:6',
            'role' => 'sometimes|in:SUPER_ADMIN,ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT,ETUDIANT',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $targetUser->update($data);

        return response()->json($targetUser);
    }

    public function destroy(Request $request, $id)
    {
        $currentUser = $request->user();
        $targetUser = Utilisateur::findOrFail($id);

        // ─── Garde 1 : personne ne peut supprimer un utilisateur is_origin ───
        if ($targetUser->is_origin) {
            return response()->json(['message' => 'Le super administrateur d\'origine ne peut pas être supprimé'], 403);
        }

        // ─── Garde 2 : un utilisateur ne peut pas se supprimer lui-même ───
        if ($currentUser->id === $targetUser->id) {
            return response()->json(['message' => 'Vous ne pouvez pas supprimer votre propre compte'], 403);
        }

        // ─── Garde 3 : un super admin non-origin ne peut pas supprimer un autre super admin ───
        if (!$currentUser->is_origin && $targetUser->role === 'SUPER_ADMIN') {
            return response()->json(['message' => 'Seul le super administrateur d\'origine peut supprimer un autre super administrateur'], 403);
        }

        $targetUser->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }

    // ─── Profil : consulter son propre profil ───────────────────────
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    // ─── Profil : modifier son propre profil ────────────────────────
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'nom' => 'sometimes|string',
            'prenom' => 'sometimes|string',
            'email' => 'sometimes|email|unique:utilisateurs,email,' . $user->id,
            'telephone' => 'nullable|string',
            'current_password' => 'required_with:new_password|string',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        // Vérifier l'ancien mot de passe si changement demandé
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Le mot de passe actuel est incorrect'], 422);
            }
            $data['password'] = Hash::make($request->new_password);
        }

        // Retirer les champs de mot de passe du tableau de mise à jour
        unset($data['current_password'], $data['new_password'], $data['new_password_confirmation']);

        $user->update($data);

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'utilisateur' => $user->fresh(),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = Utilisateur::where('email', $request->email)->first();

        // Vérification des identifiants
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Les identifiants sont incorrects'], 401);
        }

        $user->tokens()->delete();

        $user->update([
            'derniereConnexion' => now(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'utilisateur' => $user,
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie'], 200);
    }

    public function export()
    {
        return Excel::download(
            new UtilisateursExport,
            'utilisateurs_' . date('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new UtilisateursImport, $request->file('file'));
            return response()->json(['message' => 'Importation réussie'], 200);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return response()->json([
                'message' => 'Erreurs de validation dans le fichier',
                'errors' => $e->failures(),
            ], 422);
        }
    }
}
