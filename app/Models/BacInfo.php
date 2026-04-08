<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BacInfo extends Model
{
    protected $fillable = [
        'etudiant_id',
        'serie',
        'mention',
        'anneeObtention',
        'lycee',
        'academie',
        'copieScaneeUrl'
    ];

    public function etudiant() {
        return $this->belongsTo(Etudiant::class);
    }
}