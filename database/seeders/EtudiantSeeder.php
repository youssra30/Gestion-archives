<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Etudiant;

class EtudiantSeeder extends Seeder
{
    public function run(): void
    {
        Etudiant::create([
            'utilisateur_id' => null,
            'cne' => 'CNE123456',
            'cin' => 'AB123456',
            'nom' => 'Karimi',
            'prenom' => 'Alae',
            'dateNaissance' => '2000-05-10',
            'lieuNaissance' => 'Casablanca',
            'nationalite' => 'Marocaine',
            'sexe' => 'MASCULIN',
            'adresse' => 'Hay Hassani',
            'telephone' => '0612345678',
            'email' => 'alae@example.com',
            'nomPere' => 'Ahmed',
            'nomMere' => 'Fatima',
            'adresseParents' => 'Casablanca',
            'filiere' => 'Informatique',
            'anneeInscription' => 2022,
            'etablissementOrigine' => 'Lycée Hassan II',
            'etablissementAccueil' => 'EST Casablanca',
            'photoUrl' => null,
        ]);

        Etudiant::create([
            'utilisateur_id' => null,
            'cne' => 'CNE654321',
            'cin' => 'CD654321',
            'nom' => 'Lahlou',
            'prenom' => 'Sara',
            'dateNaissance' => '2001-08-15',
            'lieuNaissance' => 'Rabat',
            'nationalite' => 'Marocaine',
            'sexe' => 'FEMININ',
            'adresse' => 'Agdal',
            'telephone' => '0623456789',
            'email' => 'sara@example.com',
            'nomPere' => 'Mohamed',
            'nomMere' => 'Khadija',
            'adresseParents' => 'Rabat',
            'filiere' => 'Gestion',
            'anneeInscription' => 2023,
            'etablissementOrigine' => 'Lycée Moulay Youssef',
            'etablissementAccueil' => 'ENCG Rabat',
            'photoUrl' => null,
        ]);
    }
}