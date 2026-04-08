<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'dossier_id',
        'type_document',
        'nomFichier',
        'cheminStockage',
        'dateAjout',
        'ajoute_par',
        'taille',
        'format'
    ];

    public function dossier() {
        return $this->belongsTo(DossierArchive::class);
    }

    public function utilisateur() {
        return $this->belongsTo(Utilisateur::class, 'ajoute_par');
    }
}