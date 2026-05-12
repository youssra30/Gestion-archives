<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // ⚡ بدل Model بـ Authenticatable
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'username',
        'email',
        'password',
        'telephone',
        'role',
        'is_origin',
        'derniereConnexion'
    ];

    protected $casts = [
        'is_origin' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relations
    public function documents() {
        return $this->hasMany(Document::class, 'ajoute_par');
    }

    public function mouvements() {
        return $this->hasMany(Mouvement::class, 'effectue_par');
    }

    public function reclamations() {
        return $this->hasMany(Reclamation::class, 'traite_par');
    }
}