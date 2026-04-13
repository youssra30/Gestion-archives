<?php

namespace App\Http\Controllers;

use App\Models\Mouvement;
use App\Models\DossierArchive;
use App\Exports\MouvementsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MouvementController extends Controller
{
    /**
     * عرض جميع الحركات
     */
    public function index() {
        return response()->json(Mouvement::with(['dossier.etudiant', 'utilisateur'])->orderBy('created_at', 'desc')->get());
    }

    /**
     * عرض حركة محددة
     */
    public function show($id) {
        return response()->json(Mouvement::with(['dossier.etudiant', 'utilisateur'])->findOrFail($id));
    }

    /**
     * إنشاء حركة جديدة
     */
    public function store(Request $request) {
        $data = $request->validate([
            'dossier_id'=>'required|exists:dossier_archives,id',
            'type_mouvement'=>'required|in:DEPOT_INITIAL,RETRAIT_TEMP,RETOUR,TRANSFERT_DEF,CONSULTATION,RESTITUTION',
            'dateMouvement'=>'required|date',
            'motif'=>'required|string',
            'destination'=>'nullable|string',
            'dateRetourPrevu'=>'nullable|date|after_or_equal:dateMouvement',
            'statut'=>'required|in:EN_COURS,TERMINE,EN_RETARD,ANNULE'
        ]);

        // ✅ تصحيح: استعمال request()->user()
        $data['effectue_par'] = $request->user() ? $request->user()->id : null;
        
        $mouvement = Mouvement::create($data);
        
        if ($data['type_mouvement'] === 'RETRAIT_TEMP') {
            DossierArchive::find($data['dossier_id'])->update(['statut' => 'RETIRE']);
        } elseif ($data['type_mouvement'] === 'RETOUR') {
            DossierArchive::find($data['dossier_id'])->update(['statut' => 'COMPLET']);
        }

        return response()->json($mouvement, 201);
    }

    /**
     * تحديث حركة (تسجيل العودة)
     */
    public function update(Request $request, $id) {
        $mouvement = Mouvement::findOrFail($id);
        
        $data = $request->validate([
            'dateRetourEffectif'=>'nullable|date',
            'statut'=>'sometimes|in:EN_COURS,TERMINE,EN_RETARD,ANNULE',
        ]);

        if ($request->has('dateRetourEffectif')) {
            $data['statut'] = 'TERMINE';
            DossierArchive::find($mouvement->dossier_id)->update(['statut' => 'COMPLET']);
        }

        $mouvement->update($data);
        
        return response()->json($mouvement);
    }

    /**
     * حذف حركة
     */
    public function destroy($id) {
        $mouvement = Mouvement::findOrFail($id);
        
        if ($mouvement->type_mouvement === 'RETRAIT_TEMP' && $mouvement->statut !== 'TERMINE') {
            DossierArchive::find($mouvement->dossier_id)->update(['statut' => 'COMPLET']);
        }
        
        $mouvement->delete();
        return response()->json(['message'=>'Mouvement supprimé']);
    }

    /**
     * 📤 خروج مؤقت
     */
    public function retraitTemp(Request $request)
    {
        $data = $request->validate([
            'dossier_id' => 'required|exists:dossier_archives,id',
            'destination' => 'required|string',
            'motif' => 'required|string',
            'dateRetourPrevu' => 'required|date|after:today',
        ]);

        // ✅ تصحيح: استعمال request()->user()
        $user = $request->user();

        $mouvement = Mouvement::create([
            'dossier_id' => $data['dossier_id'],
            'type_mouvement' => 'RETRAIT_TEMP',
            'dateMouvement' => now(),
            'motif' => $data['motif'],
            'destination' => $data['destination'],
            'effectue_par' => $user ? $user->id : null,
            'dateRetourPrevu' => $data['dateRetourPrevu'],
            'statut' => 'EN_COURS',
        ]);

        DossierArchive::find($data['dossier_id'])->update(['statut' => 'RETIRE']);

        return response()->json($mouvement, 201);
    }

    /**
     * 🔄 تسجيل العودة
     */
    public function retour($id)
    {
        $mouvement = Mouvement::findOrFail($id);
        
        if ($mouvement->type_mouvement !== 'RETRAIT_TEMP') {
            return response()->json(['message' => 'Ce mouvement n\'est pas un retrait temporaire'], 400);
        }

        $mouvement->update([
            'dateRetourEffectif' => now(),
            'statut' => 'TERMINE',
        ]);

        DossierArchive::find($mouvement->dossier_id)->update(['statut' => 'COMPLET']);

        return response()->json($mouvement);
    }

    /**
     * 📊 الحركات الجارية
     */
    public function enCours()
    {
        $mouvements = Mouvement::with(['dossier.etudiant', 'utilisateur'])
            ->where('statut', 'EN_COURS')
            ->where('type_mouvement', 'RETRAIT_TEMP')
            ->orderBy('dateRetourPrevu', 'asc')
            ->get();
            
        return response()->json($mouvements);
    }

    /**
     * ⚠️ الحركات المتأخرة
     */
    public function enRetard()
    {
        $mouvements = Mouvement::with(['dossier.etudiant', 'utilisateur'])
            ->where('statut', 'EN_COURS')
            ->where('type_mouvement', 'RETRAIT_TEMP')
            ->where('dateRetourPrevu', '<', now())
            ->orderBy('dateRetourPrevu', 'asc')
            ->get();
            
        return response()->json($mouvements);
    }

    /**
     * 📤 تصدير جميع الحركات إلى Excel
     */
    public function export()
    {
        return Excel::download(new MouvementsExport, 'mouvements_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}