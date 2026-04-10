<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use Illuminate\Http\Request;
use App\Imports\EtudiantsImport;
use Maatwebsite\Excel\Facades\Excel;

class EtudiantController extends Controller
{
    public function index() {
        return response()->json(Etudiant::with('dossiers','bacInfo')->get());
    }

    public function show($id) {
        return response()->json(Etudiant::with('dossiers','bacInfo')->findOrFail($id));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'cne'=>'required|unique:etudiants',
            'cin'=>'required|unique:etudiants',
            'nom'=>'required|string',
            'prenom'=>'required|string',
            'dateNaissance'=>'required|date',
            'lieuNaissance'=>'required|string',
            'nationalite'=>'required|string',
            'sexe'=>'required|in:MASCULIN,FEMININ',
            'adresse'=>'required|string',
            'telephone'=>'required|string',
            'email'=>'required|email|unique:etudiants',
            'filiere'=>'required|string',
            'anneeInscription'=>'required|integer',
            'utilisateur_id'=>'nullable|exists:utilisateurs,id'
        ]);

        return response()->json(Etudiant::create($data), 201);
    }

    public function update(Request $request, $id) {
        $etudiant = Etudiant::findOrFail($id);
        $etudiant->update($request->all());
        return response()->json($etudiant);
    }

    public function destroy($id) {
        Etudiant::findOrFail($id)->delete();
        return response()->json(['message'=>'Etudiant supprimé']);
    }

    public function import(Request $request) 
    {

    $request->validate([
        'file' => 'required'
    ]);

    try {
        Excel::import(new EtudiantsImport, $request->file('file'));
        
        return response()->json([
            'message' => 'Les étudiants ont été importés avec succès !'
        ], 200);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erreur lors de l\'importation : ' . $e->getMessage()
        ], 500);
    }
    }
}