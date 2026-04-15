<?php

namespace App\Exports;

use App\Models\Mouvement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MouvementsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Mouvement::with(['dossier.etudiant', 'utilisateur'])->get();
    }
    
    public function headings(): array
    {
        return [
            'ID', 'Numéro Dossier', 'Étudiant', 'Type Mouvement', 
            'Date Mouvement', 'Motif', 'Provenance', 'Destination',
            'Effectué par', 'Document retiré', 'Date retour prévu',
            'Date retour effectif', 'Statut', 'Date création'
        ];
    }
    
    public function map($mouvement): array
    {
        return [
            $mouvement->id,
            $mouvement->dossier->numeroDossier ?? '',
            $mouvement->dossier->etudiant->nom . ' ' . $mouvement->dossier->etudiant->prenom ?? '',
            $mouvement->type_mouvement,
            $mouvement->dateMouvement,
            $mouvement->motif,
            $mouvement->provenance,
            $mouvement->destination,
            $mouvement->utilisateur->nom . ' ' . $mouvement->utilisateur->prenom ?? '',
            $mouvement->documentRetire ? 'Oui' : 'Non',
            $mouvement->dateRetourPrevu,
            $mouvement->dateRetourEffectif,
            $mouvement->statut,
            $mouvement->created_at,
        ];
    }
}