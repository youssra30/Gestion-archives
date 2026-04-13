<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DossierArchive extends Model
{
    protected $table = 'dossier_archives';

    protected $fillable = [
        'numeroDossier',
        'etudiant_id',
        'typeCas',
        'statut',
        'dateArchivage',
        'localisation',
        'observations'
    ];

    
    public function etudiant() {
        return $this->belongsTo(Etudiant::class);
    }

   
    public function documents() {
        return $this->hasMany(Document::class, 'dossier_id');
    }

   
    public function mouvements() {
        return $this->hasMany(Mouvement::class, 'dossier_id');
    }

    
    public function reclamations() {
        return $this->hasMany(Reclamation::class, 'dossier_id');
    }

  
    public function transferts() {
        
        return $this->hasMany(TransfertExterne::class, 'dossier_id');
        
        
    }
}