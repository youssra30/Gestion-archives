<?php

namespace App\Imports;

use App\Models\Etudiant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EtudiantsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Etudiant([
            'cne'              => $row['cne'],
            'cin'              => $row['cin'],
            'nom'              => $row['nom'],
            'prenom'           => $row['prenom'],
            'dateNaissance'    => $row['datenaissance'], 
            'lieuNaissance'    => $row['lieunaissance'],
            'nationalite'      => $row['nationalite'],
            'sexe'             => $row['sexe'],
            'adresse'          => $row['adresse'],
            'telephone'        => $row['telephone'],
            'email'            => $row['email'],
            'filiere'          => $row['filiere'],
            'anneeInscription' => $row['anneeinscription'],
            'utilisateur_id'   => null, 
        ]);
    }
}