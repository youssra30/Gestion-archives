<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class UtilisateurController extends Controller
{
    public function index() {
        return response()->json(Utilisateur::all());
    }

    public function show($id) {
        return response()->json(Utilisateur::findOrFail($id));
    }

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

    public function destroy($id) {
        Utilisateur::findOrFail($id)->delete();
        return response()->json(['message'=>'Utilisateur supprimé']);
    }
}