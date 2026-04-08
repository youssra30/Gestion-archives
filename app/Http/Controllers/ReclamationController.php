<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    public function index() {
        return response()->json(Reclamation::with('dossier','utilisateur')->get());
    }

    public function show($id) {
        return response()->json(Reclamation::with('dossier','utilisateur')->findOrFail($id));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'dossier_id'=>'required|exists:dossier_archives,id',
            'demandeur'=>'required|string',
            'typeDemande'=>'required',
            'dateDemande'=>'required|date',
            'statut'=>'required',
            'documentsDemandes'=>'nullable|array',
            'motif'=>'nullable|string',
            'traite_par'=>'nullable|exists:utilisateurs,id',
            'reponse'=>'nullable|string',
            'dateTraitement'=>'nullable|date'
        ]);

        return response()->json(Reclamation::create($data), 201);
    }

    public function update(Request $request, $id) {
        $reclamation = Reclamation::findOrFail($id);
        $reclamation->update($request->all());
        return response()->json($reclamation);
    }

    public function destroy($id) {
        Reclamation::findOrFail($id)->delete();
        return response()->json(['message'=>'Reclamation supprimée']);
    }
}