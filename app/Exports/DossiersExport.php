<?php

namespace App\Exports;

use App\Models\DossierArchive;
use App\Helpers\NormalizeFiliereHelper;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DossiersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Filtre optionnel par filière normalisée (GEER, IAA, IAC, TDI, CP).
     */
    protected ?string $filiereFilter;

    public function __construct(?string $filiereFilter = null)
    {
        $this->filiereFilter = $filiereFilter;
    }

    public function collection()
    {
        $dossiers = DossierArchive::with(['etudiant', 'documents'])->get();

        // Filtrer : ne garder que les dossiers dont l'étudiant a une filière officielle
        return $dossiers->filter(function ($dossier) {
            if (!$dossier->etudiant) return false;

            $normalized = NormalizeFiliereHelper::normalize($dossier->etudiant->filiere);

            if ($this->filiereFilter) {
                return $normalized === $this->filiereFilter;
            }

            return NormalizeFiliereHelper::isOfficielle($dossier->etudiant->filiere);
        });
    }
    
    public function headings(): array
    {
        return [
            'ID', 'Numéro Dossier', 'CNE Etudiant', 'Nom Etudiant', 'Prénom Etudiant',
            'Filière', 'Filière Normalisée',
            'Type Cas', 'Statut', 'Date Archivage', 'Localisation', 
            'Observations', 'Nombre de Documents', 'Date Création', 'Date Modification'
        ];
    }
    
    public function map($dossier): array
    {
        return [
            $dossier->id,
            $dossier->numeroDossier,
            $dossier->etudiant->cne ?? '',
            $dossier->etudiant->nom ?? '',
            $dossier->etudiant->prenom ?? '',
            $dossier->etudiant->filiere ?? '',
            NormalizeFiliereHelper::normalize($dossier->etudiant->filiere ?? ''),
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