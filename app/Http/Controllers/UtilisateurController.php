<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Exports\UtilisateursExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    /**
     * عرض قائمة جميع المستخدمين
     */
    public function index()
    {
        return response()->json(Utilisateur::all());
    }

    /**
     * عرض مستخدم محدد
     */
    public function show($id)
    {
        return response()->json(Utilisateur::findOrFail($id));
    }

    /**
     * إضافة مستخدم جديد
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'username' => 'required|unique:utilisateurs',
            'email' => 'required|email|unique:utilisateurs',
            'password' => 'required|string|min:6',
            'telephone' => 'nullable|string',
            'role' => 'required|in:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT,ETUDIANT'
        ]);

        $data['password'] = Hash::make($data['password']);
        
        $user = Utilisateur::create($data);
        
        return response()->json($user, 201);
    }

    /**
     * تحديث بيانات مستخدم
     */
    public function update(Request $request, $id)
    {
        $user = Utilisateur::findOrFail($id);
        
        $data = $request->validate([
            'nom' => 'sometimes|string',
            'prenom' => 'sometimes|string',
            'username' => 'sometimes|unique:utilisateurs,username,' . $id,
            'email' => 'sometimes|email|unique:utilisateurs,email,' . $id,
            'telephone' => 'nullable|string',
            'role' => 'sometimes|in:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT,ETUDIANT'
        ]);
        
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        return response()->json($user);
    }

    /**
     * حذف مستخدم
     */
    public function destroy($id)
    {
        $user = Utilisateur::findOrFail($id);
        $user->delete();
        
        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }

    /**
     * تسجيل الدخول
     */
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

        // 5. Mettre à jour la date de dernière connexion
        $user->update([
            'derniereConnexion' => now()
        ]);

        // 6. Supprimer les anciens tokens (optionnel)
        $user->tokens()->delete();

        // 7. Génération du token Sanctum
        $token = $user->createToken('admin_token')->plainTextToken;

        // 8. Réponse avec le token
        return response()->json([
            'message' => 'Connexion réussie',
            'utilisateur' => $user,
            'token' => $token
        ], 200);
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Déconnexion réussie'
        ], 200);
    }

    /**
     * ✅ تصدير جميع المستخدمين إلى Excel
     */
    public function export()
    {
        return Excel::download(new UtilisateursExport, 'utilisateurs_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}