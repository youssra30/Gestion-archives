<?php

namespace App\Http\Controllers;

use App\Models\TransfertExterne;
use App\Exports\TransfertsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class TransfertExterneController extends Controller
{
    public function index() {
        return response()->json(TransfertExterne::with('dossier')->get());
    }

    public function show($id) {
        return response()->json(TransfertExterne::with('dossier')->findOrFail($id));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'dossier_id'=>'required|exists:dossier_archives,id',
            'ecoleOrigine'=>'required|string',
            'ecoleDestination'=>'required|string',
            'dateDemande'=>'required|date',
            'dateValidation'=>'nullable|date',
            'statut'=>'required',
            'documentsTransmis'=>'nullable|array',
            'referenceCourrier'=>'nullable|string'
        ]);

        return response()->json(TransfertExterne::create($data), 201);
    }

    public function update(Request $request, $id) {
        $transfert = TransfertExterne::findOrFail($id);

        $data = $request->validate([
            'dossier_id'         => 'sometimes|exists:dossier_archives,id',
            'ecoleOrigine'       => 'sometimes|string|max:255',
            'ecoleDestination'   => 'sometimes|string|max:255',
            'dateDemande'        => 'sometimes|date',
            'dateValidation'     => 'nullable|date',
            'statut'             => 'sometimes|string|max:50',
            'documentsTransmis'  => 'nullable|array',
            'referenceCourrier'  => 'nullable|string|max:255',
        ]);

        $transfert->update($data);

        return response()->json($transfert);
    }

    public function destroy($id) {
        TransfertExterne::findOrFail($id)->delete();
        return response()->json(['message'=>'Transfert supprimé']);
    }

   
    public function export()
    {
        return Excel::download(new TransfertsExport, 'transferts_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}