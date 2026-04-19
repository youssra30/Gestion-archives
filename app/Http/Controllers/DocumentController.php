<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index() {
        return response()->json(Document::with('dossier','utilisateur')->get());
    }

    public function show($id) {
        return response()->json(Document::with('dossier','utilisateur')->findOrFail($id));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'dossier_id'=>'required|exists:dossier_archives,id',
            'type_document'=>'required',
            'nomFichier'=>'required|string',
            'cheminStockage'=>'required|string',
            'ajoute_par'=>'required|exists:utilisateurs,id',
            'taille'=>'required|integer',
            'format'=>'required|string'
        ]);

        return response()->json(Document::create($data), 201);
    }

    public function update(Request $request, $id) {
        $document = Document::findOrFail($id);

        $data = $request->validate([
            'dossier_id'      => 'sometimes|exists:dossier_archives,id',
            'type_document'   => 'sometimes|string|max:100',
            'nomFichier'      => 'sometimes|string|max:255',
            'cheminStockage'  => 'sometimes|string|max:500',
            'ajoute_par'      => 'sometimes|exists:utilisateurs,id',
            'taille'          => 'sometimes|integer',
            'format'          => 'sometimes|string|max:50',
        ]);

        $document->update($data);

        return response()->json($document);
    }

    public function destroy($id) {
        Document::findOrFail($id)->delete();
        return response()->json(['message'=>'Document supprimé']);
    }
}