<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mouvement extends Model
{
    protected $fillable = [
        'dossier_id',
        'type_mouvement',
        'dateMouvement',
        'motif',
        'provenance',
        'destination',
        'effectue_par',
        'documentRetire',
        'documentsRetires',
        'dateRetourPrevu',
        'dateRetourEffectif',
        'statut'
    ];

    protected $casts = [
        'documentsRetires' => 'array'
    ];

    public function dossier() {
        return $this->belongsTo(DossierArchive::class);
    }

    public function utilisateur() {
        return $this->belongsTo(Utilisateur::class, 'effectue_par');
    }
}