<?php

namespace App\Http\Controllers;

use App\Models\DossierArchive;
use App\Models\Mouvement;
use App\Exports\DossiersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $dossier->update($request->all());
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

    /**
     * 🔍 Rechercher un dossier (Agent Accueil)
     */
    public function rechercher(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:2',
        ]);

        $search = $request->search;

        $dossiers = DossierArchive::with(['etudiant', 'documents', 'mouvements'])
            ->where('numeroDossier', 'LIKE', "%{$search}%")
            ->orWhereHas('etudiant', function ($query) use ($search) {
                $query->where('cne', 'LIKE', "%{$search}%")
                      ->orWhere('cin', 'LIKE', "%{$search}%")
                      ->orWhere('nom', 'LIKE', "%{$search}%")
                      ->orWhere('prenom', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'dossiers' => $dossiers,
            'total' => $dossiers->total(),
        ]);
    }

    /**
     * 📋 Consulter un dossier avec tous les détails (Agent Accueil)
     */
    public function consulter($id)
    {
        $dossier = DossierArchive::with([
            'etudiant',
            'etudiant.bacInfo',
            'documents',
            'mouvements' => function ($query) {
                $query->orderBy('dateMouvement', 'desc');
            },
            'reclamations',
            'transferts'
        ])->findOrFail($id);

        $historique = Mouvement::where('dossier_id', $id)
            ->with('utilisateur')
            ->orderBy('dateMouvement', 'desc')
            ->get();

        return response()->json([
            'dossier' => $dossier,
            'historique' => $historique,
        ]);
    }

    /**
     * 📥 Réceptionner un nouveau dossier (Agent Accueil)
     */
    public function receptionner(Request $request)
    {
        $data = $request->validate([
            'numeroDossier' => 'required|unique:dossier_archives,numeroDossier',
            'etudiant_id' => 'required|exists:etudiants,id',
            'typeCas' => 'required|in:ADMISSION,AUTRE_VILLE,ABANDON_CYCLE,TRANSFERT_SORTANT,TRANSFERT_ENTRANT,LAUREAT,ABANDON_PREPA,DEMI_PENSION,PENSION_COMPLETE',
            'observations' => 'nullable|string',
        ]);

        $dossier = DossierArchive::create([
            'numeroDossier' => $data['numeroDossier'],
            'etudiant_id' => $data['etudiant_id'],
            'typeCas' => $data['typeCas'],
            'statut' => 'COMPLET',
            'dateArchivage' => now(),
            'localisation' => 'Archives',
            'observations' => $data['observations'] ?? null,
        ]);

        // ✅ التصحيح: استعمال $request->user()
        $user = $request->user();

        Mouvement::create([
            'dossier_id' => $dossier->id,
            'type_mouvement' => 'DEPOT_INITIAL',
            'dateMouvement' => now(),
            'motif' => 'Réception de dossier',
            'destination' => 'Archives',
            'effectue_par' => $user ? $user->id : null,
            'statut' => 'TERMINE',
        ]);

        return response()->json([
            'message' => 'Dossier réceptionné avec succès',
            'dossier' => $dossier->load('etudiant'),
        ], 201);
    }
}