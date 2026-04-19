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

        $data = $request->validate([
            'dossier_id'          => 'sometimes|exists:dossier_archives,id',
            'type_mouvement'      => 'sometimes|string|max:100',
            'dateMouvement'       => 'sometimes|date',
            'motif'               => 'sometimes|string|max:500',
            'provenance'          => 'nullable|string|max:255',
            'destination'         => 'nullable|string|max:255',
            'effectue_par'        => 'sometimes|exists:utilisateurs,id',
            'documentRetire'      => 'nullable|boolean',
            'documentsRetires'    => 'nullable|array',
            'dateRetourPrevu'     => 'nullable|date',
            'dateRetourEffectif'  => 'nullable|date',
            'statut'              => 'sometimes|string|max:50',
        ]);

        $mouvement->update($data);

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