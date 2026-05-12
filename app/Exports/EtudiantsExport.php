<?php

namespace App\Exports;

use App\Models\Etudiant;
use App\Helpers\NormalizeFiliereHelper;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EtudiantsExport implements FromCollection, WithHeadings, WithMapping
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
        $etudiants = Etudiant::with('bacInfo')->get();

        // Filtrer : ne garder que les étudiants dont la filière normalisée
        // correspond aux filières officielles (GEER, IAA, IAC, TDI, CP)
        return $etudiants->filter(function ($etudiant) {
            $normalized = NormalizeFiliereHelper::normalize($etudiant->filiere);

            // Si un filtre spécifique est demandé
            if ($this->filiereFilter) {
                return $normalized === $this->filiereFilter;
            }

            // Sinon, ne garder que les filières officielles
            return NormalizeFiliereHelper::isOfficielle($etudiant->filiere);
        });
    }
    
    public function headings(): array
    {
        return [
            'ID', 'CNE', 'CIN', 'Nom', 'Prénom', 'Date Naissance', 
            'Lieu Naissance', 'Nationalité', 'Sexe', 'Adresse', 
            'Téléphone', 'Email', 'Nom Père', 'Nom Mère', 
            'Adresse Parents', 'Filière', 'Filière Normalisée', 'Année Inscription',
            'Etablissement Origine', 'Etablissement Accueil', 
            'Série Bac', 'Mention Bac', 'Année Bac', 'Lycée', 'Académie',
            'Date Création', 'Date Modification'
        ];
    }
    
    public function map($etudiant): array
    {
        return [
            $etudiant->id,
            $etudiant->cne,
            $etudiant->cin,
            $etudiant->nom,
            $etudiant->prenom,
            $etudiant->dateNaissance,
            $etudiant->lieuNaissance,
            $etudiant->nationalite,
            $etudiant->sexe,
            $etudiant->adresse,
            $etudiant->telephone,
            $etudiant->email,
            $etudiant->nomPere,
            $etudiant->nomMere,
            $etudiant->adresseParents,
            $etudiant->filiere,
            NormalizeFiliereHelper::normalize($etudiant->filiere),
            $etudiant->anneeInscription,
            $etudiant->etablissementOrigine,
            $etudiant->etablissementAccueil,
            $etudiant->bacInfo->serie ?? '',
            $etudiant->bacInfo->mention ?? '',
            $etudiant->bacInfo->anneeObtention ?? '',
            $etudiant->bacInfo->lycee ?? '',
            $etudiant->bacInfo->academie ?? '',
            $etudiant->created_at,
            $etudiant->updated_at,
        ];
    }
}