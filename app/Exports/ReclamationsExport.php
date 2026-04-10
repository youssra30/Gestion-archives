<?php

namespace App\Exports;

use App\Models\Reclamation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReclamationsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Reclamation::with(['dossier.etudiant', 'utilisateur'])->get();
    }
    
    public function headings(): array
    {
        return [
            'ID', 'Numéro Dossier', 'Étudiant', 'Demandeur', 
            'Type Demande', 'Date Demande', 'Date Traitement',
            'Statut', 'Motif', 'Traitée par', 'Réponse', 'Date création'
        ];
    }
    
    public function map($reclamation): array
    {
        return [
            $reclamation->id,
            $reclamation->dossier->numeroDossier ?? '',
            $reclamation->dossier->etudiant->nom . ' ' . $reclamation->dossier->etudiant->prenom ?? '',
            $reclamation->demandeur,
            $reclamation->typeDemande,
            $reclamation->dateDemande,
            $reclamation->dateTraitement,
            $reclamation->statut,
            $reclamation->motif,
            $reclamation->utilisateur->nom . ' ' . $reclamation->utilisateur->prenom ?? '',
            $reclamation->reponse,
            $reclamation->created_at,
        ];
    }
}