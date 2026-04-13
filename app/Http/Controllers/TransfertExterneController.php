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
        $transfert->update($request->all());
        return response()->json($transfert);
    }

    public function destroy($id) {
        TransfertExterne::findOrFail($id)->delete();
        return response()->json(['message'=>'Transfert supprimé']);
    }

    /**
     * ✅ تصدير جميع التحويلات الخارجية إلى Excel
     */
    public function export()
    {
        return Excel::download(new TransfertsExport, 'transferts_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}