<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Exports\EtudiantsExport;
use App\Imports\EtudiantsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    public function index() {
        return response()->json(Etudiant::with('dossiers','bacInfo')->get());
    }

    public function show($id) {
        return response()->json(Etudiant::with('dossiers','bacInfo')->findOrFail($id));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'cne'=>'required|unique:etudiants',
            'cin'=>'required|unique:etudiants',
            'nom'=>'required|string',
            'prenom'=>'required|string',
            'dateNaissance'=>'required|date',
            'lieuNaissance'=>'required|string',
            'nationalite'=>'required|string',
            'sexe'=>'required|in:MASCULIN,FEMININ',
            'adresse'=>'required|string',
            'telephone'=>'required|string',
            'email'=>'required|email|unique:etudiants',
            'filiere'=>'required|string',
            'anneeInscription'=>'required|integer',
            'utilisateur_id'=>'nullable|exists:utilisateurs,id'
        ]);

        return response()->json(Etudiant::create($data), 201);
    }

    public function update(Request $request, $id) {
        $etudiant = Etudiant::findOrFail($id);

        $data = $request->validate([
            'cne'               => 'sometimes|unique:etudiants,cne,' . $id,
            'cin'               => 'sometimes|unique:etudiants,cin,' . $id,
            'nom'               => 'sometimes|string|max:255',
            'prenom'            => 'sometimes|string|max:255',
            'dateNaissance'     => 'sometimes|date',
            'lieuNaissance'     => 'sometimes|string|max:255',
            'nationalite'       => 'sometimes|string|max:100',
            'sexe'              => 'sometimes|in:MASCULIN,FEMININ',
            'adresse'           => 'sometimes|string|max:500',
            'telephone'         => 'sometimes|string|max:20',
            'email'             => 'sometimes|email|unique:etudiants,email,' . $id,
            'filiere'           => 'sometimes|string|max:255',
            'anneeInscription'  => 'sometimes|integer',
            'nomPere'           => 'nullable|string|max:255',
            'nomMere'           => 'nullable|string|max:255',
            'adresseParents'    => 'nullable|string|max:500',
            'etablissementOrigine' => 'nullable|string|max:255',
            'etablissementAccueil' => 'nullable|string|max:255',
            'photoUrl'          => 'nullable|string|max:500',
            'utilisateur_id'    => 'nullable|exists:utilisateurs,id',
        ]);

        $etudiant->update($data);

        return response()->json($etudiant);
    }

    public function destroy($id) {
        Etudiant::findOrFail($id)->delete();
        return response()->json(['message'=>'Etudiant supprimé']);
    }
    
    
    public function export()
    {
        return Excel::download(new EtudiantsExport, 'etudiants_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new EtudiantsImport;
            Excel::import($import, $request->file('file'));

            $failures = $import->failures();
            $errors = $import->errors();

            if ($failures->count() > 0 || count($errors) > 0) {
                return response()->json([
                    'message' => 'Importation partielle — certaines lignes ont été ignorées',
                    'skipped' => $failures->count(),
                ], 200);
            }

            return response()->json(['message' => 'Importation réussie'], 200);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return response()->json([
                'message' => 'Erreurs de validation dans le fichier',
                'errors' => collect($e->failures())->map(fn($f) => "Ligne {$f->row()}: {$f->errors()[0]}")->take(5),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'importation: ' . $e->getMessage(),
            ], 500);
        }
    }
}