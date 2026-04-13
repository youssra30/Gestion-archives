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
        $request->validate([
            'dossier_id' => 'required|exists:dossier_archives,id',
            'type_document' => 'required',
            'file' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5000', // قبول الملفات الحقيقية
            'ajoute_par' => 'required|exists:utilisateurs,id',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // كايتحفظ في storage/app/public/documents
            $path = $file->store('documents', 'public'); 

            $document = Document::create([
                'dossier_id' => $request->dossier_id,
                'type_document' => $request->type_document,
                'nomFichier' => $file->getClientOriginalName(),
                'cheminStockage' => $path,
                'ajoute_par' => $request->ajoute_par,
                'taille' => $file->getSize(),
                'format' => $file->getClientOriginalExtension()
            ]);

            return response()->json($document, 201);
        }

        return response()->json(['message' => 'File not found'], 400);
    }

    public function update(Request $request, $id) {
        $document = Document::findOrFail($id);
        $document->update($request->all());
        return response()->json($document);
    }

    public function destroy($id) {
        Document::findOrFail($id)->delete();
        return response()->json(['message'=>'Document supprimé']);
    }
}