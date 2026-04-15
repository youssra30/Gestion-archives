<?php

namespace App\Exports;

use App\Models\DossierArchive;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DossiersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return DossierArchive::with(['etudiant', 'documents'])->get();
    }
    
    public function headings(): array
    {
        return [
            'ID', 'Numéro Dossier', 'CNE Etudiant', 'Nom Etudiant', 'Prénom Etudiant',
            'Type Cas', 'Statut', 'Date Archivage', 'Localisation', 
            'Observations', 'Nombre de Documents', 'Date Création', 'Date Modification'
        ];
    }
    
    public function map($dossier): array
    {
        return [
            $dossier->id,
            $dossier->numeroDossier,
            $dossier->etudiant->cne,
            $dossier->etudiant->nom,
            $dossier->etudiant->prenom,
            $dossier->typeCas,
            $dossier->statut,
            $dossier->dateArchivage,
            $dossier->localisation,
            $dossier->observations,
            $dossier->documents->count(),
            $dossier->created_at,
            $dossier->updated_at,
        ];
    }
}