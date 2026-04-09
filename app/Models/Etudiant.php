<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DossierArchive;
use App\Models\BacInfo;

class Etudiant extends Model
{
    protected $fillable = [
        'cne',
        'cin',
        'nom',
        'prenom',
        'dateNaissance',
        'lieuNaissance',
        'nationalite',
        'sexe',
        'adresse',
        'telephone',
        'email',
        'nomPere',
        'nomMere',
        'adresseParents',
        'filiere',
        'anneeInscription',
        'etablissementOrigine',
        'etablissementAccueil',
        'photoUrl',
        'utilisateur_id'
    ];

    public function dossiers() {
        return $this->hasMany(DossierArchive::class);
    }

    public function bacInfo() {
        return $this->hasOne(BacInfo::class);
    }
}