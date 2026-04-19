<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Exports\ReclamationsExport;
use Maatwebsite\Excel\Facades\Excel;
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

        $data = $request->validate([
            'dossier_id'        => 'sometimes|exists:dossier_archives,id',
            'demandeur'         => 'sometimes|string|max:255',
            'typeDemande'       => 'sometimes|string|max:100',
            'dateDemande'       => 'sometimes|date',
            'statut'            => 'sometimes|string|max:50',
            'documentsDemandes' => 'nullable|array',
            'motif'             => 'nullable|string|max:500',
            'traite_par'        => 'nullable|exists:utilisateurs,id',
            'reponse'           => 'nullable|string|max:1000',
            'dateTraitement'    => 'nullable|date',
        ]);

        $reclamation->update($data);

        return response()->json($reclamation);
    }

    public function destroy($id) {
        Reclamation::findOrFail($id)->delete();
        return response()->json(['message'=>'Reclamation supprimée']);
    }

    
    public function export()
    {
        return Excel::download(new ReclamationsExport, 'reclamations_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}