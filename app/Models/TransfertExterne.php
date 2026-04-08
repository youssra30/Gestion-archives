<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransfertExterne extends Model
{
    protected $fillable = [
        'dossier_id',
        'ecoleOrigine',
        'ecoleDestination',
        'dateDemande',
        'dateValidation',
        'statut',
        'documentsTransmis',
        'referenceCourrier'
    ];

    protected $casts = [
        'documentsTransmis' => 'array'
    ];

    public function dossier() {
        return $this->belongsTo(DossierArchive::class);
    }
}