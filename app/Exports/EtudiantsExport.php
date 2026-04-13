<?php

namespace App\Exports;

use App\Models\Etudiant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EtudiantsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Etudiant::with('bacInfo')->get();
    }
    
    public function headings(): array
    {
        return [
            'ID', 'CNE', 'CIN', 'Nom', 'Prénom', 'Date Naissance', 
            'Lieu Naissance', 'Nationalité', 'Sexe', 'Adresse', 
            'Téléphone', 'Email', 'Nom Père', 'Nom Mère', 
            'Adresse Parents', 'Filière', 'Année Inscription',
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