<?php

namespace App\Imports;

use App\Models\Etudiant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;

class EtudiantsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    public function model(array $row)
    {
        // Ignore les lignes vides
        if (empty($row['cne']) && empty($row['cin'])) {
            return null;
        }

        // Génère un email unique si absent ou vide
        $email = $row['email'] ?? '';
        if (empty(trim($email))) {
            $email = strtolower(trim($row['prenom'] ?? 'x') . '.' . trim($row['nom'] ?? 'x')) . '.' . uniqid() . '@etu.ensa.ma';
        }

        // Vérifie si l'étudiant existe déjà (par CNE ou CIN)
        $existing = Etudiant::where('cne', $row['cne'])->orWhere('cin', $row['cin'])->orWhere('email', $email)->first();
        if ($existing) {
            // Met à jour l'existant au lieu de dupliquer
            $existing->update(array_filter([
                'nom'                  => $row['nom'] ?? null,
                'prenom'               => $row['prenom'] ?? null,
                'filiere'              => $row['filiere'] ?? null,
                'telephone'            => $row['telephone'] ?? null,
                'adresse'              => $row['adresse'] ?? null,
            ]));
            return null;
        }

        // Colonnes du fichier Excel réel :
        // cne, cin, nom, prenom, dateNaissance, lieuNaissance, nationalite, sexe,
        // adresse, telephone, email, nomPere, nomMere, adresseParents, filiere,
        // anneeInscription, etablissementOrigine, etablisssementAccueil, photoUrl,
        // serie, mention, aneeObtention, lycee, academie, copieScaneerUrl, typeCas

        return new Etudiant([
            'cne'                  => $row['cne'],
            'cin'                  => $row['cin'],
            'nom'                  => $row['nom'],
            'prenom'               => $row['prenom'],
            'dateNaissance'        => $row['datenaissance'] ?? null,
            'lieuNaissance'        => $row['lieunaissance'] ?? '',
            'nationalite'          => $row['nationalite'] ?? 'Marocaine',
            'sexe'                 => strtoupper($row['sexe'] ?? 'MASCULIN'),
            'adresse'              => $row['adresse'] ?? '',
            'telephone'            => $row['telephone'] ?? '',
            'email'                => $email,
            'nomPere'              => $row['nompere'] ?? null,
            'nomMere'              => $row['nommere'] ?? null,
            'adresseParents'       => $row['adresseparents'] ?? null,
            'filiere'              => $row['filiere'] ?? '',
            'anneeInscription'     => $row['anneeinscription'] ?? $row['annee'] ?? date('Y'),
            'etablissementOrigine' => $row['etablissementorigine'] ?? null,
            'etablissementAccueil' => $row['etablisssementaccueil'] ?? $row['etablissementaccueil'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'cne'    => 'required|string',
            'cin'    => 'required|string',
            'nom'    => 'required|string',
            'prenom' => 'required|string',
        ];
    }
}
