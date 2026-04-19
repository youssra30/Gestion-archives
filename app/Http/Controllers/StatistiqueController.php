<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Etudiant;
use App\Models\DossierArchive;
use App\Models\Mouvement;
use App\Models\Reclamation;
use App\Models\TransfertExterne;
use App\Models\Document;

class StatistiqueController extends Controller
{
    /**
     * Statistiques globales du système d'archives.
     * Renvoie les compteurs et répartitions nécessaires au Dashboard.
     */
    public function index()
    {
        return response()->json([
            // Compteurs principaux
            'total_utilisateurs'  => Utilisateur::count(),
            'total_etudiants'     => Etudiant::count(),
            'total_dossiers'      => DossierArchive::count(),
            'total_documents'     => Document::count(),
            'total_mouvements'    => Mouvement::count(),
            'total_reclamations'  => Reclamation::count(),
            'total_transferts'    => TransfertExterne::count(),

            // Répartitions
            'dossiers_par_statut' => DossierArchive::selectRaw('statut, COUNT(*) as total')
                ->groupBy('statut')
                ->pluck('total', 'statut'),

            'mouvements_par_type' => Mouvement::selectRaw('type_mouvement, COUNT(*) as total')
                ->groupBy('type_mouvement')
                ->pluck('total', 'type_mouvement'),

            'reclamations_par_statut' => Reclamation::selectRaw('statut, COUNT(*) as total')
                ->groupBy('statut')
                ->pluck('total', 'statut'),

            'utilisateurs_par_role' => Utilisateur::selectRaw('role, COUNT(*) as total')
                ->groupBy('role')
                ->pluck('total', 'role'),
        ]);
    }
}
