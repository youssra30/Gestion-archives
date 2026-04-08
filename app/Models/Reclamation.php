<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    protected $fillable = [
        'dossier_id',
        'demandeur',
        'typeDemande',
        'dateDemande',
        'dateTraitement',
        'statut',
        'documentsDemandes',
        'motif',
        'traite_par',
        'reponse'
    ];

    protected $casts = [
        'documentsDemandes' => 'array'
    ];

    public function dossier() {
        return $this->belongsTo(DossierArchive::class);
    }

    public function utilisateur() {
        return $this->belongsTo(Utilisateur::class, 'traite_par');
    }
}