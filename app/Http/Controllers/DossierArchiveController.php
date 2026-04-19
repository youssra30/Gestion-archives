<?php

namespace App\Http\Controllers;

use App\Models\DossierArchive;
use App\Exports\DossiersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class DossierArchiveController extends Controller
{
    public function index() {
        return response()->json(DossierArchive::with(['etudiant','documents','mouvements','reclamations','transferts'])->get());
    }

    public function show($id) {
        return response()->json(DossierArchive::with(['etudiant','documents','mouvements','reclamations','transferts'])->findOrFail($id));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'numeroDossier'=>'required|unique:dossier_archives',
            'etudiant_id'=>'required|exists:etudiants,id',
            'typeCas'=>'required',
            'statut'=>'required',
            'dateArchivage'=>'nullable|date',
            'localisation'=>'nullable|string',
            'observations'=>'nullable|string'
        ]);

        return response()->json(DossierArchive::create($data), 201);
    }

    public function update(Request $request, $id) {
        $dossier = DossierArchive::findOrFail($id);

        $data = $request->validate([
            'numeroDossier' => 'sometimes|unique:dossier_archives,numeroDossier,' . $id,
            'etudiant_id'   => 'sometimes|exists:etudiants,id',
            'typeCas'       => 'sometimes|string|max:100',
            'statut'        => 'sometimes|string|max:50',
            'dateArchivage' => 'nullable|date',
            'localisation'  => 'nullable|string|max:255',
            'observations'  => 'nullable|string|max:1000',
        ]);

        $dossier->update($data);

        return response()->json($dossier);
    }

    public function destroy($id) {
        DossierArchive::findOrFail($id)->delete();
        return response()->json(['message'=>'Dossier supprimé']);
    }
    
    public function export()
    {
        return Excel::download(new DossiersExport, 'dossiers_archives_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}