<?php

namespace App\Http\Controllers;

use App\Models\BacInfo;
use Illuminate\Http\Request;

class BacInfoController extends Controller
{
    public function index() {
        return response()->json(BacInfo::with('etudiant')->get());
    }

    public function show($id) {
        return response()->json(BacInfo::with('etudiant')->findOrFail($id));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'etudiant_id'=>'required|exists:etudiants,id',
            'serie'=>'required|string',
            'mention'=>'required|in:TRES_BIEN,BIEN,ASSEZ_BIEN,PASSABLE',
            'anneeObtention'=>'required|integer',
            'lycee'=>'required|string',
            'academie'=>'required|string',
            'copieScaneeUrl'=>'nullable|string'
        ]);

        return response()->json(BacInfo::create($data), 201);
    }

    public function update(Request $request, $id) {
        $bac = BacInfo::findOrFail($id);
        $bac->update($request->all());
        return response()->json($bac);
    }

    public function destroy($id) {
        BacInfo::findOrFail($id)->delete();
        return response()->json(['message'=>'BacInfo supprimé']);
    }
}