<?php

namespace App\Http\Controllers;

use App\Models\Mouvement;
use App\Exports\MouvementsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class MouvementController extends Controller
{
    public function index() {
        return response()->json(Mouvement::with('dossier','utilisateur')->get());
    }

    public function show($id) {
        return response()->json(Mouvement::with('dossier','utilisateur')->findOrFail($id));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'dossier_id'=>'required|exists:dossier_archives,id',
            'type_mouvement'=>'required',
            'dateMouvement'=>'required|date',
            'motif'=>'required|string',
            'provenance'=>'nullable|string',
            'destination'=>'nullable|string',
            'effectue_par'=>'required|exists:utilisateurs,id',
            'documentRetire'=>'nullable|boolean',
            'documentsRetires'=>'nullable|array',
            'dateRetourPrevu'=>'nullable|date',
            'dateRetourEffectif'=>'nullable|date',
            'statut'=>'required'
        ]);

        return response()->json(Mouvement::create($data), 201);
    }

    public function update(Request $request, $id) {
        $mouvement = Mouvement::findOrFail($id);
        $mouvement->update($request->all());
        return response()->json($mouvement);
    }

    public function destroy($id) {
        Mouvement::findOrFail($id)->delete();
        return response()->json(['message'=>'Mouvement supprimé']);
    }

    
    public function export()
    {
        return Excel::download(new MouvementsExport, 'mouvements_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}