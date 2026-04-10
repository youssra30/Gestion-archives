<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Exports\UtilisateursExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
                    'message' => 'SuperAdmin peut فقط créer ADMIN_SYSTEME'
                ], 403);
            }
        } elseif ($currentUser->role === 'ADMIN_SYSTEME') {
            if (!in_array($request->role, ['RESPONSABLE_ARCHIVES', 'AGENT_ACCUEIL'])) {
                return response()->json([
                    'message' => 'Admin peut فقط créer Responsable ou Agent'
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'Accès interdit'
            ], 403);
        }

        $user = Utilisateur::create([
            'username' => $request->username,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Utilisateur::findOrFail($id);

        $data = $request->validate([
            'username' => 'sometimes|unique:utilisateurs,username,' . $id,
            'nom' => 'sometimes|string',
            'prenom' => 'sometimes|string',
            'email' => 'sometimes|email|unique:utilisateurs,email,' . $id,
            'telephone' => 'nullable|string',
            'password' => 'nullable|min:6',
            'role' => 'sometimes|in:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT,ETUDIANT'
        ]);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json($user);
    }

   
    public function destroy($id)
    {
        $user = Utilisateur::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    }

   
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = Utilisateur::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Les identifiants sont incorrects'
            ], 401);
        }

    
        if (!in_array($user->role, ['SUPER_ADMIN', 'ADMIN_SYSTEME'])) {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

      
        $user->tokens()->delete();

      
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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ], 200);
    }

   
    public function export()
    {
        return Excel::download(
            new UtilisateursExport,
            'utilisateurs_' . date('Y-m-d_H-i-s') . '.xlsx'
        );
    }
}