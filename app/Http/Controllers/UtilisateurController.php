<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    public function index() {
        return response()->json(Utilisateur::all());
    }

    public function show($id) {
        return response()->json(Utilisateur::findOrFail($id));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'nom'=>'required|string',
            'prenom'=>'required|string',
            'username'=>'required|string|unique:utilisateurs',
            'email'=>'required|email|unique:utilisateurs',
            'password'=>'required|string',
            'telephone'=>'nullable|string',
            'role'=>'nullable|in:ADMIN_SYSTEME,RESPONSABLE_ARCHIVES,AGENT_ACCUEIL,CONSULTANT,ETUDIANT'
        ]);
        $data['password'] = bcrypt($data['password']);
        return response()->json(Utilisateur::create($data), 201);
    }

    public function update(Request $request, $id) {
        $user = Utilisateur::findOrFail($id);
        $user->update($request->all());
        return response()->json($user);
    }

    public function destroy($id) {
        Utilisateur::findOrFail($id)->delete();
        return response()->json(['message'=>'Utilisateur supprimé']);
    }
}